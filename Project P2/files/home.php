<?php 
session_start();
require_once('../helpers/helpers.php');

$klantnummer = $_SESSION["KlantNr"];
$series = getActiveSeries($db, $klantnummer);
$randomSerie = getRandomSerie($db);
$randomSeries = getRandomSeries($db);  

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="home">
    <nav class="full-screen-nav">
        <div class="nav-content">
            <img src="../images/HOBO_logo.png" alt="" class="logo">
            
                <a href="#">Home</a>
                <a href="#">Contact</a>
                <a href="#">Logout</a>
                <a href="#">Profile</a>
            </div>
        </div>
    </nav>
    <main class="homepage">
        <section class="big-image">
            <?php foreach($randomSerie as $serie) { ?>
                <div class="big-image-content">
                    <h3><?php echo $serie['SerieTitel']; ?></h3>
                    <img src="../images/0000<?php echo $serie['SerieID']; ?>.jpg" alt="" class="nav-background">
                    <img src="../images/000<?php echo $serie['SerieID']; ?>.jpg" alt="" class="nav-background">
                    <img src="../images/00<?php echo $serie['SerieID']; ?>.jpg" alt="" class="nav-background">
                </div>
            <?php } ?>
        </section>
        <section class="active-section">
            <h2>Aanbevolen</h2>
            <div class="series-container">
                <?php foreach($series as $serie) { ?>
                    <div class="serie">
                        <a href="./serie.php?id=<?php echo $serie['SerieID']; ?>">
                            <img src="../images/0000<?php echo $serie['SerieID']; ?>.jpg" alt="" >
                            <img src="../images/000<?php echo $serie['SerieID']; ?>.jpg" alt="" >
                            <img src="../images/00<?php echo $serie['SerieID']; ?>.jpg" alt="" >
                        </a>
                    </div>
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
</body>
</html>

