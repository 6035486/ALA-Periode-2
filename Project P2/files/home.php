<?php 
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
require_once('../helpers/helpers.php');

$klantnummer = $_SESSION["KlantNr"];
$series = getActiveSeries($db, $klantnummer);
$randomSerie = getRandomSerie($db);
$randomSeries = getRandomSeries($db);  
$imageSrc = "../images/"

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../script/main.js">
        
    </script>
</head>
<body class="home"> 
<nav>
        <img src="../images/HOBO_logo.png" alt="Logo">
        <article>  
            <a href="./home.php">Home</a>
            <a href="#">Contact</a>
            <a href="./profile.php">Profile</a>
            <a href="./uitlog.php">Logout</a>
        </article>
    </nav>       

    <div id="slideshow-container">
        <img id="slideshow" src="../images/gentlemen.jpg" alt="Slideshow Image">
        <img class="hidden" src="../images/crown.jpg" alt="Preload Image">
    </div>
    <main class="homepage">
    
        <section class="active-section">
            <h2>Aanbevolen</h2>
            <div class="series-container">
                <?php foreach($series as $serie) { ?>
                    <a href="#">
                        <img src="<?php switch (strlen($serie["SerieID"])) {
                            case 1:
                                echo $imageSrc . "0000" . $serie["SerieID"] . ".jpg";
                                break;
                            
                            case 2:
                                echo $imageSrc . "000" . $serie["SerieID"] . ".jpg";
                                break;
                            
                            case 3:
                                echo $imageSrc . "00" . $serie["SerieID"] . ".jpg";
                                break;
                            case 4:
                                echo $imageSrc . "0" . $serie["SerieID"] . ".jpg";
                                break;
                            
                            default:
                                
                                break;
                        } ?>" alt="" srcset="">
                       
                        </a> 
                <?php } ?>
                
            </div>
        </section>
        <section class="active-section">
            <h2>Popular</h2>
            <div class="series-container">
                <?php foreach($randomSeries as $serie) { ?>
                    <div class="serie">
                    <a href="./serie.php?id=<?php echo $serie['SerieID']; ?>">
                        <img src="../images/0000<?php echo $serie['SerieID']; ?>.jpg" alt="" >
                        <img src="../images/000<?php echo $serie['SerieID']; ?>.jpg" alt="" >
                        <img src="../images/00<?php echo $serie['SerieID']; ?>.jpg" alt="" >
                  </a>  </div>
                <?php } ?>
            </div>
        </section>
    </main>
    <footer class="footer">
    <div class="footer__container">
        <div class="footer__info">
            <h3 class="footer__title">Bedrijfsnaam</h3>
            <p>1234 Adresstraat,<br>Stad, Land</p>
            <p>Telefoon: (123) 456-7890</p>
            <p>Email: info@bedrijf.nl</p>
        </div>

        <div class="footer__links">
            <h3 class="footer__title">Snelle Links</h3>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="#">Over Ons</a></li>
                <li><a href="#">Diensten</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>

        <div class="footer__social">
            <h3 class="footer__title">Volg ons</h3>
            <ul>
                <li><a href="#">Facebook</a></li>
                <li><a href="#">Twitter</a></li>
                <li><a href="#">LinkedIn</a></li>
                <li><a href="#">Instagram</a></li>
            </ul>
        </div>
    </div>
</footer>

</body>
</html>

