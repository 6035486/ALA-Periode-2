<?php
require_once ('../connect/connect.php');

function getActiveSeries($db, $klantnummer) {
    $sql = "SELECT  serie.*
            FROM serie
            INNER JOIN serie_genre ON serie.SerieID = serie_genre.SerieID
            INNER JOIN genre ON serie_genre.GenreID = genre.GenreID
            INNER JOIN klant ON klant.Genre = genre.GenreNaam
            WHERE serie.Actief = 1 AND klant.KlantNr = :klantnummer";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':klantnummer', $klantnummer, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getRandomSerie($db){
    $sql = "SELECT * FROM serie WHERE Actief = 1 ORDER BY RAND() LIMIT 1";
    $result = $db->query($sql);
    return $result->fetchAll(PDO::FETCH_ASSOC);
}

function getRandomSeries($db){
    $sql = "SELECT * FROM serie WHERE Actief = 1 ORDER BY RAND() LIMIT 10"; 
    $result = $db->query($sql);
    return $result->fetchAll(PDO::FETCH_ASSOC);
}
function getSerieInfo($db, $serieID){
    $sql = "SELECT serie.*, 
                   seizoen.Rang,
                   seizoen.SeizoenID, 
                   seizoen.Jaar,
                   seizoen.IMDBRating, 
                   aflevering.Rang,
                   aflevering.AflTitel,
                   aflevering.Duur
            FROM serie
            INNER JOIN seizoen ON serie.serieId = seizoen.SerieID
            INNER JOIN aflevering ON seizoen.seizoenID = aflevering.seizID
            WHERE serie.serieId = :serieID";
    
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':serieID', $serieID, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
function login($db){
    
    $fixedPassword = "Wachtwoord";
    
   
    $hashedFixedPassword = password_hash($fixedPassword, PASSWORD_DEFAULT);

    
    $sql = "SELECT * FROM klant WHERE Email = ?";
    $stm = $db->prepare($sql);
    $stm->execute([$_POST['email']]);
    $selectedUser = $stm->fetch(PDO::FETCH_ASSOC);

    if (isset($selectedUser['Email'])) {
       
        if (!password_verify($_POST['password'], $hashedFixedPassword)) {
            $error = "Password or email incorrect";
        } else {
            
            $_SESSION["KlantNr"] = $selectedUser["KlantNr"];
            header("Location: home.php");
            exit();
        }
    } else {
        $error = "Password or email incorrect";}
}
