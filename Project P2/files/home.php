<?php 
require_once('../helpers/helpers.php');


$activeSeries = getActiveSeries($db); 
$nonActiveSeries = getNonActiveSeries($db); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .series-container {
            display: flex;
            overflow-x: auto; 
            white-space: nowrap; 
        }
        .serie {
            flex: 0 0 auto; 
            margin-right: 20px; 
        }
        .serie img {
            max-width: 100px; 
            height: auto; 
        }
    </style>
</head>
<body class="home">
    <main>
        <section class="active-section">
            <h2>Actieve Series</h2>
            <div class="series-container">
                <?php foreach($activeSeries as $serie) { ?>
                    <div class="serie">
                         <img src="./images/dummy.png" alt="">
                         <h3><?php echo $serie['SerieTitel']; ?></h3></div>

                <?php } ?>
            </div>
        </section>
        <section class="non-active-section">
            <h2>Niet-actieve Series</h2>
            <div class="series-container">
                <?php foreach($nonActiveSeries as $serie) { ?>
                    <div class="serie">
                        <img src="./images/dummy.png" alt="">
                        <h3><?php echo $serie['SerieTitel']; ?></h3> 
                    </div>
                <?php } ?>
            </div>
        </section>
    </main>
</body>
</html>