<?php
require_once('../helpers/helpers.php');
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$klantnummer = $_SESSION['KlantNr'];

function profile($db, $klantnummer){
    $sql = "SELECT * FROM klant WHERE KlantNr = :klantnummer";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':klantnummer', $klantnummer, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$profileData = profile($db, $klantnummer);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<nav>
        <img src="../images/HOBO_logo.png" alt="Logo">
        <article>
            <a href="./home.php">Home</a>
            <a href="#">Contact</a>
            <a href="#">Profile</a>
            <a href="./logout.php">Logout</a>
        </article>
    </nav>
    <main>
        <h2>Profile</h2>
        <?php foreach($profileData as $data){?>
        <p><?php echo $data['Voornaam']?></p>
        <?php } ?>

    </main>
</body>
</html>