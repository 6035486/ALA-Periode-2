<?php 
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
</head>
<body>
<div class="row">
<div class="column">
  <img class="demo cursor" src="img_woods.jpg" style="width:100%" onclick="currentSlide(1)" alt="The Woods">
</div>
</body>
</html>
