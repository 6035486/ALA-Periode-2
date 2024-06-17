<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    header('Location: home.php');
    exit;
}

require_once('../helpers/helpers.php');

$user = new User();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'], $_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $loginUser = $user->checkPassword($email, $password);

    if ($loginUser === false) {
        $error = "Invalid login credentials";
    } else {
        $_SESSION['email'] = $loginUser['Email'];
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $loginUser['id'];
        $_SESSION['klantNr'] = $loginUser['KlantNr'];
        header("Location: home.php");
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
    <nav>
<a href="../index.php">
<img src="../images/HOBO_logo.png" alt="logo"></a>
<article>
    <a href="signup.php">Registreren</a>
</article>
</nav>
        <main>
            <h1>inloggen</h1>
            <form action="" method="post">
                <input class="" type="email" name="email" placeholder="email" required>
                <input class="" type="password" name="password" placeholder="password" required>
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