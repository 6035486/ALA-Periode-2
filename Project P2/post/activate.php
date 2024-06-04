<?php
// Dit is handiger
session_start();
require_once("../helpers/helpers.php");
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
        if(isset($_POST['serieId'])) {
            $serie = getSerieById($db, $_POST['serieId']);
            if ($serie["Actief"] == 1) {
                
                    deactivateSerie($db, $_POST['serieId']);
                
                
            }
            else if ($serie["Actief"] == 0) {
                activateSerie($db, $_POST['serieId']);
            }
        }
    }
}
header("Location: ../files/cms.php");