<?php
session_start();

if (isset($_SESSION['admin']) && $_SESSION['admin'] == true) {
    header('Location: cms.php');
    exit;
}
require_once ('../helpers/helpers.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = admminLogin($db, $_POST['username'], $_POST["password"]);
    if ($login == false) {
        $error = "invalid login credentials";
    } else {
        $_SESSION["admin"] = true;
        header("location: cms.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>inloggen</h1>
    <form action="" method="post">
        <input class="" type="text" name="username" placeholder="username" id="" required>
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
</body>

</html>