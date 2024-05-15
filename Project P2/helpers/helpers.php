<?php 
require_once('../connect/connect.php');

function getActiveSeries($db){
    $sql = "SELECT * FROM serie WHERE Actief = 1"; 
    $result = $db->query($sql);
    return $result->fetchAll(PDO::FETCH_ASSOC);
}

function getNonActiveSeries($db){
    $sql = "SELECT * FROM serie WHERE Actief = 0";
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

}

?>