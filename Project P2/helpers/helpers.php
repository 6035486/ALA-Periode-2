<?php
require_once ('../connect/connect.php');

function getActiveSeries($db)
{
    $sql = "SELECT * FROM serie WHERE Actief = 1";
    $result = $db->query($sql);
    return $result->fetchAll(PDO::FETCH_ASSOC);
}

function getNonActiveSeries($db)
{
    $sql = "SELECT * FROM serie WHERE Actief = 0";
    $result = $db->query($sql);
    return $result->fetchAll(PDO::FETCH_ASSOC);
}
function resetPassword($db)
{
    $testpassword = "Password";
    $password = password_hash($testpassword, PASSWORD_DEFAULT);
    $sql =
        "UPDATE klant
    SET password = ?
    WHERE 1=1";
    $stm = $db->prepare($sql);
    $stm->execute([$password]);
    $stm->fetch(PDO::FETCH_ASSOC);
}

function checkPassword($db, $email, $password)
{
    $sql = "SELECT * FROM klant WHERE email = ?";
    $stm = $db->prepare($sql);
    $stm->execute([$email]);
    $selectedUser = $stm->fetch(PDO::FETCH_ASSOC);
    if (password_verify($password, $selectedUser["password"])) {
        return $selectedUser['KlantNr'];
    } else {
        return false;
    }
}

function search($db, $search)
{
    $searchLike = "%".$search."%";
    $query = $db->prepare("SELECT * FROM serie WHERE Actief = 1 AND SerieTitel LIKE :search LIMIT 50");
    $query->execute(["search" => $searchLike]);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    usort($results, fn($a, $b) => levenshtein($search, $a["SerieTitel"])<=> levenshtein($search, $b["SerieTitel"]));
   
    return $results;

}
function adminSearch($db, $search)
{
    $searchLike = "%".$search."%";
    $query = $db->prepare("SELECT * FROM serie WHERE SerieTitel LIKE :search LIMIT 1000");
    $query->execute(["search" => $searchLike]);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    usort($results, fn($a, $b) => levenshtein($search, $a["SerieTitel"])<=> levenshtein($search, $b["SerieTitel"]));
   
    return $results;
}

function admminLogin($db, $user, $password) {
    $sql = "SELECT * FROM users WHERE username = :username";
    $stm = $db->prepare($sql);
    $stm->execute(["username" => $user]);
    $selectedUser = $stm->fetch(PDO::FETCH_ASSOC);
    if (password_verify($password, $selectedUser["password"])) {
        return $selectedUser;
    } else {
        return false;
    }
}