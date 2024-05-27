<?php


if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    header('Location: home.php');
    exit;
}
require_once('../helpers/helpers.php');
session_start(); 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    login($db);}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $login = checkPassword($db, $_POST['email'],$_POST["password"]);
    if($login == false){
        $error = "invalid login credentials";
    }
    else {
        $_SESSION["email"] = $_POST["email"];
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['KlantNr'] = $user['KlantNr'];
        header("location: home.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="../css/style.css">
       
    </head>
    <body class="login">
        <header>
            <img class="logo" src="../images/HOBO_beeldmerk.png" alt="">
            <a  class="" href="signup.php">Signup</a>
        </header>
        <main>
            <h1>inloggen</h1>
            <form action="" method="post">
                <input class="" type="email" name="email" placeholder="email" id="" required>
                <input class="" type="password" name="password" placeholder="password" id="" required>
                <input class="submit" type="submit" value="Login">
            </form>
            <p class="error">
                <?php
                        if (isset($error)) {
                            echo $error;
                        }
                ?>
            </p>
        </main>
    </body>
</html>