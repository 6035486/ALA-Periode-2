<?php
// Dit is handiger
session_start();
require_once("../helpers/helpers.php");
$serieHelper = new Serie();
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
        if(isset($_POST['serieId'])) {
            $serie = $serieHelper->getSerieById($_POST['serieId']);
            if ($serie["Actief"] == 1) {
                
                $serieHelper->deactivateSerie($_POST['serieId']);
                
                
            }
            else if ($serie["Actief"] == 0) {
                $serieHelper->activateSerie($_POST['serieId']);
            }
        }
    }
}
header("Location: ../files/cms.php");