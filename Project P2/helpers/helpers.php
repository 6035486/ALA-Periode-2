<?php
require_once '../connect/connect.php';

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
function adminSearch($db, $search = false, $offset = 0)
{
    if ($search != false) {
        $searchLike = "%".$search."%";
        $offset = $offset * 30;
        $query = $db->prepare("SELECT SELECT  serie.*
        FROM serie
        INNER JOIN serie_genre ON serie.SerieID = serie_genre.SerieID
        INNER JOIN genre ON serie_genre.GenreID = genre.GenreID
        INNER JOIN klant ON klant.Genre = genre.GenreNaam
        WHERE SerieTitel LIKE ? ORDER BY SerieTitel LIMIT 30 offset $offset");
        $query->execute([$searchLike]);
    }else {
        $offset = $offset * 30;
        $query = $db->prepare("SELECT *
        FROM serie
        INNER JOIN serie_genre ON serie.SerieID = serie_genre.SerieID
        INNER JOIN genre ON serie_genre.GenreID = genre.GenreID
        INNER JOIN klant ON klant.Genre = genre.GenreNaam
        ORDER BY SerieTitel LIMIT 30 offset $offset");
        $query->execute();
    }
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    usort($results, fn($a, $b) => levenshtein($search, $a["SerieTitel"])<=> levenshtein($search, $b["SerieTitel"]));
   
    return $results;
}

function adminLogin($db, $user, $password) {
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
function getSerieById($db, $serieId){
    $sql = "SELECT * FROM serie WHERE SerieID = :id";
    $stm = $db->prepare($sql);
    $stm->execute(['id' => $serieId]);
    $result = $stm->fetch(PDO::FETCH_ASSOC);
    if(empty($result)){
        return false;
    }
    return $result;
}

function activateSerie($db, $serieId) {
    // Error codes: 
    // Code 1, serie reeds actief.
    // Code 2, Serie niet gevonden.
    // Code 3, Serie actief maken niet gelukt
    $serie = getSerieById($db, $serieId);
    if ($serie == false){
        throw new Exception("No serie found where SerieId = ". $serieId, 2);
    }
    if (isset($serie["Actief"])){
        if($serie["Actief"] == 1){
            throw new Exception("Serie already actief", 1);
        } else {
            try {
                $sql = "UPDATE serie SET Actief = 1 WHERE SerieID = :id";
                $stm = $db->prepare($sql);
                $stm->execute(["id" => $serieId]);
                return true;
            } catch (\Throwable $th) {
                throw new Exception("Failed to activate serie where SerieId = : ". $serieId, 3);
            }
        }
    }
}
function deactivateSerie($db, $serieId) {
    // Error codes: 
    // Code 1, serie reeds deactief.
    // Code 2, Serie niet gevonden.
    // Code 3, Serie nonactief maken niet gelukt
    $serie = getSerieById($db, $serieId);
    if ($serie == false){
        return new Error("No serie found where SerieId = ". $serieId, 2);
    }
    if (isset($serie["Actief"])){
        if($serie["Actief"] == 0){
            return new Error("Serie already deactivated", 1);
        } else {
            try {
                $sql = "UPDATE serie SET Actief = 0 WHERE SerieID = :id";
                $stm = $db->prepare($sql);
                $stm->execute(["id" => $serieId]);
                return true;
            } catch (\Throwable $th) {
                return new Error("Failed to deactivate  serie where SerieId = : ". $serieId, 3);
            }
        }
    }
}
