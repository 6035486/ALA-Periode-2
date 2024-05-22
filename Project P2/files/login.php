<?php
require_once('../helpers/helpers.php');
session_start(); 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $fixedPassword = "Wachtwoord";
    
   
    $hashedFixedPassword = password_hash($fixedPassword, PASSWORD_DEFAULT);

    
    $sql = "SELECT * FROM klant WHERE Email = ?";
    $stm = $db->prepare($sql);
    $stm->execute([$_POST['email']]);
    $selectedUser = $stm->fetch(PDO::FETCH_ASSOC);

    if (isset($selectedUser['Email'])) {
       
        if (!password_verify($_POST['password'], $hashedFixedPassword)) {
            $error = "Password or email incorrect";
        } else {
            
            $_SESSION["KlantNr"] = $selectedUser["KlantNr"];
            header("Location: home.php");
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
                <label for="email"><p class="">email</p></label>    
                <input class="" type="email" name="email" placeholder="email" id="" required>
                <label for="password"><p class="">password</p></label>
                <input class="" type="password" name="password" placeholder="password" id="" required>
                <input class="" type="submit" value="Login">
            </form>
            <p class="">
                <?php
                        if (isset($error)) {
                            echo $error;
                        }
                ?>
            </p>
        </main>
    </body>

</html>