<?php
require_once('../helpers/helpers.php');

$serieID = $_GET['id'];
$serieInfo = getSerieInfo($db, $serieID);
$imageSrc = "../images/";
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
                    foreach ($serieInfo as $info) {
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
                    <ul>
                        <?php
                        foreach ($serieInfo as $info) {
                            if ($info['SeizoenID'] == $info['SeizID']) { 
                                 if($info[''] = $info['Rang']){ ?>
                                <div class="episodes">
                                    <img src="../images/dummy.png" alt="Episode Image">
                                    <li><?php echo $info['AflTitel']; ?> - Duration: <?php echo $info['Duur']; ?></li>
                                </div>
                            <?php }}
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