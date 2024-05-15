<?php
require_once('../helpers/helpers.php');
if($_SERVER["REQUEST_METHOD"] == "POST"){
   
    $sql = "SELECT * FROM klant WHERE Email = ?";
    $stm = $db->prepare($sql);
    $stm->execute([$_POST['email']]);
    $selectedUser = $stm->fetch(PDO::FETCH_ASSOC);
    if(isset($selectedUser['Email'])){
        if (!password_verify($_POST['password'], $selectedUser['password'])) {
            $error = "Password or email incorrect";
        }
        if (password_verify($_POST["password"], $selectedUser["password"])) {
            $_SESSION["KlantNr"] = $selectedUser["KlantNr"];
            header("index.php");
            exit();
        }
    } else {
        $error = "Password or email incorrect";
    }
}
?>


    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="styling/main.css">
        <link rel="stylesheet" href="styling/login.css">
    </head>
    <body>
        <header>
            <img class="logo" src="./img/HOBO_beeldmerk.png" alt="">
            <a  class="grijs backgroundgroen" href="signup.php">Signup</a>
        </header>
        <main>
            <h1 class="groen">inloggen</h1>
            <form action="" method="post">
                <label for="email"><p class="groen">email</p></label>    
                <input class="grijs input backgroundgroen" type="email" name="email" placeholder="email" id="" required>
                <label for="password"><p class="groen">password</p></label>
                <input class="grijs input backgroundgroen" type="password" name="password" placeholder="password" id="" required>
                <input class="backgroundgroen submitbtn" type="submit" value="Login">
            </form>
            <p class="groen">
                <?php
                        if (isset($error)) {
                            echo $error;
                        }
                ?>
            </p>
        </main>
    </body>

</html>