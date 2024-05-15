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


function redirectUponError(string $error){
    $queryString = http_build_query(array("error" => $error));
    header("Location: login.php?$queryString");
    exit();
}

require_once("database_connect.php");

if (!isset($_POST['email']) || empty($_POST['email'])) {
    redirectUponError("Email required for login");
}
if (!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
    redirectUponError('Email invalid');
}
if (!isset($_POST['password']) || empty($_POST['password'])) {
    redirectUponError("Password required for login");
}

$sql = "SELECT * FROM klant WHERE email = ?";
$stm = $pdo->prepare($sql); 
$stm->execute([$_POST['email']]);
$selectedUser = $stm->fetch(PDO::FETCH_ASSOC);

if (!$selectedUser || !password_verify($_POST['password'], $selectedUser['password'])) {
    redirectUponError("Password or email incorrect");
}

session_start();
$_SESSION["KlantNr"] = $selectedUser["KlantNr"];
header("Location: home.php"); 
exit();
?>