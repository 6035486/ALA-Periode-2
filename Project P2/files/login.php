<?php
require_once('../helpers/helpers.php');
require_once('../connect/connect.php');
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $login = checkPassword($db, $_POST['email'],$_POST["password"]);
    if($login == false){
        $error = "invalid login credentials";
    }
    else {
        $_SESSION["KlantNr"] = $login;
        header("location: ../index.php");
        exit();
    }
}
?>


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