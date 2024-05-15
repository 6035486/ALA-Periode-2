<?php 
require_once('../helpers/helpers.php');
$activeSeries = getActiveSeries($db);
$randomSerie = getRandomSerie($db) 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../script/main.js"></script>

</head>
<body class="home">
    <main>
        <nav>
        <?php foreach($randomSerie as $serie) { ?>
            <h3><?php echo $serie['SerieTitel']; ?></h3>
            <img src="../images/0000<?php echo $serie['SerieID']; ?>.jpg" alt="" >
            <img src="../images/000<?php echo $serie['SerieID']; ?>.jpg" alt="">
            <img src="../images/00<?php echo $serie['SerieID']; ?>.jpg" alt="">
            <?php } ?>

        </nav>

    <section class="active-section">
            <h2>Aanbevolen</h2>
            <article class="series-container">
                <?php foreach($activeSeries as $serie) { ?>
                    <article class="serie">
                         <img src="../images/0000<?php echo $serie['SerieID']; ?>.jpg" alt="" >
                         <img src="../images/000<?php echo $serie['SerieID']; ?>.jpg" alt="">
                         <img src="../images/00<?php echo $serie['SerieID']; ?>.jpg" alt="">
                         <h3><?php echo $serie['SerieTitel']; ?></h3>
                </article></a>
                <?php } ?>
                </article>
        </section>
        
    </main>
</body>
</html>