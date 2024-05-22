<?php
require_once('../helpers/helpers.php');

$serieID = $_GET['id'];
$serieInfo = getSerieInfo($db, $serieID);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css">
  
</head>
<body>
<nav>
        <img src="../images/HOBO_logo.png" alt="Logo">
        <article>
            <a href="./home.php">Home</a>
            <a href="#">Contact</a>
            <a href="#">Profile</a>
            <a href="#">Logout</a>
        </article>
    </nav>
<main class="info">
        <div class="series-info">
            <div class="info-text">
                <h2><?php echo $serieInfo[0]['SerieTitel']; ?></h2>
                <p>Rating: <?php echo $serieInfo[0]['IMDBRating']; ?></p>
            </div>
            <img src="../images/0000<?php echo $serieInfo[0]['SerieID']; ?>.jpg" alt="" class="series-image">
            <img src="../images/000<?php echo $serieInfo[0]['SerieID']; ?>.jpg" alt="" class="series-image">
            <img src="../images/00<?php echo $serieInfo[0]['SerieID']; ?>.jpg" alt="" class="series-image">
        </div>

        <div class="seasons">
            <div class="dropdown">
                
                <select id="season-select" onchange="navigateToSeason(this)">
                    <?php
                    $seasons = [];
                    foreach ($serieInfo as $info) {
                        if (!in_array($info['SeizoenID'], $seasons)) {
                            $seasons[] = $info['SeizoenID']; ?>
                            <option value="season-<?php echo $info['SeizoenID']; ?>">Season <?php echo $info['SeizoenID']; ?></option>
                        <?php }
                    }
                    ?>
                </select>
            </div>

            <?php
            foreach ($seasons as $seasonID) { ?>
                <div class="season" id="season-<?php echo $seasonID; ?>">
                    <h3>Season <?php echo $seasonID; ?></h3>
                    <ul>
                        <?php
                        foreach ($serieInfo as $info) {
                            if ($info['SeizoenID'] == $seasonID) { ?>
                            <div class="episodes">
                            <img src="../images/dummy.png" alt="">
                                <li><?php echo $info['AflTitel']; ?> - Duration: <?php echo $info['Duur']; ?></li></div>
                            <?php }
                        }
                        ?>
                    </ul>
                </div>
            <?php }
            ?>
        </div>
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
                <li><a href="#">Home</a></li>
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


    <script>
        function navigateToSeason(select) {
            var seasonID = select.value;
            document.querySelectorAll('.season').forEach(function(season) {
                season.style.display = 'none';
            });
            document.getElementById(seasonID).style.display = 'block';
        }
        
        
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('season-<?php echo $seasons[0]; ?>').style.display = 'block';
        });
    </script>
</body>
</html>