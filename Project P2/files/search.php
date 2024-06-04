<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
require_once('../helpers/helpers.php');
if (isset($_POST["query"])) {
    $results = search($db, $_POST["query"]);
}
$imageSrc = "../images/"
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="search">
    <header>
        <img class="logo" src="../images/HOBO_beeldmerk.png" alt="">
        
        <form method="post">
            <input type="text" name="query" id="">
            <input type="submit" value="search">
        </form>
        <a class="" href="home.php">Home</a>
    </header>
    <main>
        <section>
        <?php if (isset($results)) {
             foreach($results as $result) { 
                if ($result["Actief"] == "1") {
                    ?> <a href="./serie.php?id=<?php echo $result['SerieID']; ?>">
                        <img src="<?php switch (strlen($result["SerieID"])) {
                            case 1:
                                echo $imageSrc . "0000" . $result["SerieID"] . ".jpg";
                                break;
                            
                            case 2:
                                echo $imageSrc . "000" . $result["SerieID"] . ".jpg";
                                break;
                            
                            case 3:
                                echo $imageSrc . "00" . $result["SerieID"] . ".jpg";
                                break;
                            case 4:
                                echo $imageSrc . "0" . $result["SerieID"] . ".jpg";
                                break;
                            
                            default:
                                # code...
                                break;
                        } ?>" alt="" srcset="">
                        <p class="serietitel"><?php echo $result["SerieTitel"]; ?></p>
                        </a> <?php
                }
              }}?>
        </section>
    </main>
</body>
</html>