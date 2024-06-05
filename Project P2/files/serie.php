<?php
require_once('../helpers/helpers.php');
session_start();
$serieID = $_GET['id'];
$imageSrc = "../images/";
$Srang = getRang($db, $serieID);
$SznID = getSeason($db, 1, $serieID);
foreach($SznID as $x){
$serieInfo = getSerieInfo($db, $serieID, $x['SeizoenID'] );}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        if(isset($_POST['afleveringId'])) {
            $profileData = getProfile($db, $_SESSION['email']);
            try {
                watch($db, $profileData[0]['KlantNr'], $_POST['afleveringId']);
            } catch (PDOException $th) {
                var_dump($th);
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
                    $xx = getSeason($db, $seasonID, $serieID);
                    foreach($xx as $x){
                    $SznID = $x['SeizoenID']; }
                    
                    $serieID = $_GET['id'];
                    $serieInfo = getSerieInfo($db, $serieID, $SznID);?>
                    
                     
                    <ul>

                        <?php
                        foreach ($serieInfo as $info) {
                            
                            ?>
                            
                               
                                <div class="episodes">
                                    <img src="../images/dummy.png" alt="Episode Image">
                                    <li><?php echo $info['AflTitel']; ?> - Duration: <?php echo $info['Duur']; ?></li>
                                    <form method="post">
                                        <input type="hidden" name="afleveringId" value="<?php echo $info['AfleveringID']; ?>">
                                        <input type="submit" value="Watch">
                                    </form>
                                </div>
                            <?php 
                        }
                        ?>
                    </ul>
                </div>
            <?php }
            ?>
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
