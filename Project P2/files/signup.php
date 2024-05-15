<?php
require_once('../helpers/helpers.php');
$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($_POST['confirm_email'] !== $_POST['email']) {
        $errors['email'] = "email doesn't match";
    }
    if (strlen($_POST['password']) < 8) {
        $errors['password'] = "minimum length 8 required";
    }
    if ($_POST['confirm_password'] !== $_POST['password']){
        $errors['confirm_password'] = "password's do not match";
    }
    if(count($errors) == 0 && isset($_POST['firstname'], $_POST['lastname'], $_POST['confirm_email'], $_POST['email'],$_POST['password'],$_POST['confirm_password'],$_POST['fav_genre'])){
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        if (isset($_POST['tussenvoegsel'])) {
            $sql = 'INSERT INTO klant (Voornaam, Tussenvoegsel, Achternaam, Email, password, Genre, AboID)
            values (?,?,?,?,?,?,1);';
            $stm = $db->prepare($sql);
            $stm->execute([$_POST['firstname'], $_POST["tussenvoegsel"],$_POST['lastname'], $_POST["email"], $password, $_POST["fav_genre"]]);
        }
        else {
            $sql = 'INSERT INTO klant (Voornaam, Achternaam, Email, password, Genre, AboID)
            values (?,?,?,?,?, 1);';
            $stm = $db->prepare($sql);
            $stm->execute([$_POST['firstname'],$_POST['lastname'], $_POST["email"], $password, $_POST["fav_genre"]]);
        }

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="styling/main.css">
    <link rel="stylesheet" href="styling/login.css">
</head>
<body>
    <header>
        <img class="logo" src="./img/HOBO_beeldmerk.png" alt="">
        <a class="grijs backgroundgroen" href="login.php">Login</a>
    </header>
    <main>
        <form action="" method="post">
            <section>
            <label for="firstname"><p class="groen">First name</p></label>    
            <input class="grijs input backgroundgroen" type="text" name="firstname" placeholder="first name" id="" required>
           
            </section>
            <section>
            <label for="tussenvoegsel"><p class="groen">Tussenvoegsel</p></label>    
            <input class="grijs input backgroundgroen" type="text" name="tussenvoegsel" placeholder="tussenvoegsel" id="">
            </section>
            <section>
            <label for="lastname"><p class="groen">Last name</p></label>    
            <input class="grijs input backgroundgroen" type="text" name="lastname" placeholder="last name" id="" required>
            </section>
            <section>
            <label for="email"><p class="groen">email</p></label>    
            <input class="grijs input backgroundgroen" type="email" name="email" placeholder="email" id="" required>
            </section>
            <section>
            <label for="confirm_email"><p class="groen">confirm email</p></label>    
            <input class="grijs input backgroundgroen" type="email" name="confirm_email" placeholder="confirm email" id="" required>
            </section>
            <?php 
            if (isset($errors['email'])) {
                echo "<p class='blauw'>".$errors["email"] ."</p>";
            } 
            ?>
            <section>
            <label for="password"><p class="groen">password</p></label>
            <input class="grijs input backgroundgroen" type="password" name="password" placeholder="password" id="" required>
            </section>
            <?php 
            if (isset($errors['password'])) {
                echo "<p class='blauw'>".$errors["password"] ."</p>";
            } 
            ?>
            <section>
            <label for="confirm_password"><p class="groen">confirm password</p></label>
            <input class="grijs input backgroundgroen" type="password" name="confirm_password" placeholder="confirm password" id="" required>
            </section>
            <?php 
            if (isset($errors['confirm_password'])) {
                echo "<p class='blauw'>".$errors["confirm_password"] ."</p>";
            } 
            ?>
            <section>
            <label for="tussenvoegsel"><p class="groen">Favorite Genre</p></label>
            <select name="fav_genre" class="groen backgroundgrijs" id="" required>
                <?php
                include_once('database_connect.php');
                $sql = "SELECT GenreNaam FROM genre";
                $stm = $db->prepare($sql);
                $stm->execute();    
                $result = $stm->fetchAll();
                foreach ($result as $row) {
                    echo "<option class='groen' value='".$row["GenreNaam"]."'>".$row["GenreNaam"]."</option>";
                }
                ?>
            </select>
            </section>
           
            <input class="backgroundgroen submitbtn" type="submit" value="Signup">
        </form>
    </main>
</body>
</html>