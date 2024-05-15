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
    <link rel="stylesheet" href="../css/style.css">
    <script src="../script/main.js"></script>

</head>
<body class="home">
    <main>
        <nav>
            
        </nav>
    <section class="active-section">
            <h2>Actieve Series</h2>
            <div class="series-container">
                <?php foreach($activeSeries as $index => $serie) { ?>
                    <div class="serie" id="serie<?php echo $index; ?>" onclick="toggleSize(this)">
                         <img src="../images/0000<?php echo $serie['SerieID']; ?>.jpg" alt="" >
                         <img src="../images/000<?php echo $serie['SerieID']; ?>.jpg" alt="">
                         <img src="../images/00<?php echo $serie['SerieID']; ?>.jpg" alt="">
                         <h3><?php echo $serie['SerieTitel']; ?></h3>
                    </div>
                <?php } ?>
            </div>
        </section>
        
    </main>
</body>
</html>