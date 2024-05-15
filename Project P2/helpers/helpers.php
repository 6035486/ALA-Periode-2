<?php 
require_once('../connect/connect.php');

function getActiveSeries($db){
    $sql = "SELECT * FROM serie WHERE Actief = 1 ORDER BY RAND() LIMIT 20"; 
    $result = $db->query($sql);
    return $result->fetchAll(PDO::FETCH_ASSOC);
}
function getRandomSerie($db){
    $sql = "SELECT * FROM serie WHERE Actief = 1 ORDER BY RAND() LIMIT 1"; 
    $result = $db->query($sql);
    return $result->fetchAll(PDO::FETCH_ASSOC);
}

function resetPassword($db){
$testpassword = "Password";
$password = password_hash($testpassword, PASSWORD_DEFAULT);
$sql = 
    "UPDATE klant
    SET password = ?
    WHERE 1=1";
$stm = $db->prepare($sql);
$stm->execute([$password]);
$stm->fetch(PDO::FETCH_ASSOC);}

function genres($db){
    $sql = "SELECT * FROM genre";
    $result = $db->query($sql);
    return $result->fetchAll(PDO::FETCH_ASSOC);
}

function createAccount($db, $password, $email, $lastname, $fav_genre,){

}
?>