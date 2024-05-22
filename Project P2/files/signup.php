<?php
require_once('../helpers/helpers.php');
require_once('../connect/connect.php');
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
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="login">
    <header>
            <img class="logo" src="../images/HOBO_beeldmerk.png" alt="">
        <a class="gr" href="login.php">Login</a>
    </header>
    <main>
        <form action="" method="post">
            <section>
            <p>First name</p>
            <input class="" type="text" name="firstname" placeholder="first name" id="" required>
           
            </section>
            <section>
            <p>Tussenvoegsel</p>
            <input class="" type="text" name="tussenvoegsel" placeholder="tussenvoegsel" id="">
            </section>
            <section>
            <p>Last name</p>   
            <input class="" type="text" name="lastname" placeholder="last name" id="" required>
            </section>
            <section>
           <p>email</p>
            <input class="" type="email" name="email" placeholder="email" id="" required>
            </section>
            <section>
            <p>confirm email</p>
            <input class="" type="email" name="confirm_email" placeholder="confirm email" id="" required>
            </section>
            <?php 
            if (isset($errors['email'])) {
                echo "<p class=''>".$errors["email"] ."</p>";
            } 
            ?>
            <section>
            <p>password</p>
            <input class="" type="password" name="password" placeholder="password" id="" required>
            </section>
            <?php 
            if (isset($errors['password'])) {
                echo "<p class=''>".$errors["password"] ."</p>";
            } 
            ?>
            <section>
            <p>confirm password</p>
            <input class="n" type="password" name="confirm_password" placeholder="confirm password" id="" required>
            </section>
            <?php 
            if (isset($errors['confirm_password'])) {
                echo "<p class=''>".$errors["confirm_password"] ."</p>";
            } 
            ?>
            <section>
            <p>Favorite Genre</p>
            <select name="fav_genre" class="" id="" required>
                <?php
                $sql = "SELECT GenreNaam FROM genre";
                $stm = $db->prepare($sql);
                $stm->execute();    
                $result = $stm->fetchAll();
                foreach ($result as $row) {
                    echo "<option class='' value='".$row["GenreNaam"]."'>".$row["GenreNaam"]."</option>";
                }
                ?>
            </select>
            </section>
           
            <input class="" type="submit" value="Signup">
        </form>
    </main>
</body>
</html>