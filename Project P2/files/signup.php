<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    header('Location: home.php');
    exit;
}

require_once('../helpers/helpers.php');

$user = new User();
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = $user->register($_POST);

    if ($result['success']) {
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $result['user_id'];
        header("Location: home.php");
        exit();
    } else {
        $errors = $result['errors'];
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
            <select name="fav_genre" required>
                <?php
                $genres = new Genre();
                $genres = $genres->getAllGenres();
                foreach ($genres as $genre) {
                    echo "<option class='' value='".$genre."'>".$genre."</option>";
                }
                ?>
            </select>
            </section>
           
            <input class="" type="submit" value="Signup">
        </form>
    </main>
</body>
</html>