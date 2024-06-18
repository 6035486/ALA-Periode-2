<?php
require_once('../helpers/helpers.php');
session_start();
$serieID = $_GET['id'];
$imageSrc = "../images/";
$serie = new Serie();
$user = new User();
$stream = new Stream();

$Srang = $serie->getRang($serieID);
$SznID = $serie->getSeason(1, $serieID);

foreach($SznID as $x){
    $serieInfo = $serie->getSerieInfo($serieID, $x['SeizoenID']);
}
$serieInfo = $serie->getSerieInfo( $serieID, $x['SeizoenID'] );


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        if (isset($_POST['afleveringId'])) {
            $profileData = $user->getProfile($_SESSION['email']);
            try {
                $stream->watch($profileData[0]['KlantNr'], $_POST['afleveringId']);
            } catch (Exception $th) {
                echo "Error: " . $th->getMessage();
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Series Information</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<nav>
    
        <img src="../images/HOBO_logo.png" alt="Logo">
        <article>  
            <a href="./home.php">Home</a>
            <a href="search.php">Search</a>
            <a href="#">Contact</a>
            <a href="./profile.php">Profile</a>
            <a href="historie.php">History</a>
            <a href="./uitlog.php">Logout</a>
            <a href="./search.php">Search</a>
        </article>
    </nav>
    <main class="info">
        <div class="series-info">
            <div class="info-text">
                <h2><?php echo $serieInfo[0]['SerieTitel']; ?></h2>
                <p>Rating: <?php echo $serieInfo[0]['IMDBRating']; ?></p>
                <a href="<?php echo $serieInfo[0]['IMDBLink']?>"><?php echo $serieInfo[0]['IMDBLink']?></a>
            </div>
            <img class="series-image" src="<?php
                $serieIDLength = strlen($serieInfo[0]['SerieID']);
                switch ($serieIDLength) {
                    case 1:
                        echo $imageSrc . "0000" . $serieInfo[0]['SerieID'] . ".jpg";
                        break;
                    case 2:
                        echo $imageSrc . "000" . $serieInfo[0]['SerieID'] . ".jpg";
                        break;
                    case 3:
                        echo $imageSrc . "00" . $serieInfo[0]['SerieID'] . ".jpg";
                        break;
                    case 4:
                        echo $imageSrc . "0" . $serieInfo[0]['SerieID'] . ".jpg";
                        break;
                    default:
                        echo $imageSrc . $serieInfo[0]['SerieID'] . ".jpg";
                        break;
                }
            ?>" alt="Series Image">
        </div>
        <div class="seasons">
            <div class="dropdown">
                
                <select id="season-select" onchange="navigateToSeason(this)">
                    <?php
                    $seasons = [];
                    foreach ($Srang as $info) {
                        if (!in_array($info['Rang'], $seasons)) {
                            $seasons[] = $info['Rang']; ?>
                            <option value="season-<?php echo $info['Rang']; ?>">Season <?php echo $info['Rang']; ?></option>

                        <?php }
                    }
                    ?>                      
                </select>
            </div>
            <?php
    foreach ($seasons as $seasonID) { ?>
        <div class="season" id="season-<?php echo $seasonID; ?>">
            <h3>Season <?php echo $seasonID; ?></h3>
            <?php 
            $serieID = $_GET['id'];
            $xx = $serie->getSeason($seasonID, $serieID);
            foreach($xx as $x){
                $SznID = $x['SeizoenID'];
            }

            $serieID = $_GET['id'];
            $serieInfo = $serie->getSerieInfo($serieID, $SznID); ?>

            <div class="container">
                <button class="prev-btn">&#129084;</button>
                <div class="episodes-container" id="episodes-container-<?php echo $seasonID; ?>">
                    <ul>
                        <?php
                        foreach ($serieInfo as $info) { ?>
                            <div class="episodes">
                                <form method="post">
                                    <input type="hidden" name="afleveringId" value="<?php echo $info['afleveringID']; ?>">
                                    <button type="submit">
                                        <img src="../images/dummy.png" alt="Episode Image">
                                        <li><?php echo $info['AflTitel']; ?> - Duration: <?php echo $info['Duur']; ?></li>
                                    </button>
                                </form>
                            </div>
                            <?php
                        } ?>
                    </ul>
                </div>
                <button class="next-btn">&#129086;</button>
            </div>
        </div>
    <?php } ?>
        </div>
    </main>
    <script>
        function navigateToSeason(selectElement) {
            var seasonId = selectElement.value;
            document.querySelectorAll('.season').forEach(function(season) {
                season.style.display = 'none';
            });
            document.getElementById(seasonId).style.display = 'block';
        }

        document.addEventListener('DOMContentLoaded', function() {
            var seasonSelect = document.getElementById('season-select');
            if (seasonSelect) {
                navigateToSeason(seasonSelect);
            }
        });
    </script>
</body>
</html>
