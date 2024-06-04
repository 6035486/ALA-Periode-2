<?php
require_once ('../connect/connect.php');
function show($db, $email){
   
    $sql = "SELECT klantNr FROM klant WHERE Email = :email";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR); 
    $stmt->execute();
    $klantNmr = $stmt->fetch(PDO::FETCH_ASSOC)['klantNr']; 

    
    $sql = "SELECT
                serie.SerieTitel,
                aflevering.AfleveringID,
                aflevering.AflTitel,
                seizoen.SeizoenID,
                seizoen.Rang,
                stream.d_start,
                stream.d_eind
            FROM
                stream
            INNER JOIN
                aflevering ON stream.AflID = aflevering.AfleveringID
            INNER JOIN
                seizoen ON aflevering.SeizID = seizoen.SeizoenID
            INNER JOIN
                serie ON seizoen.SerieID = serie.SerieID
            WHERE
                stream.KlantID = :klantNr"; 

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':klantNr', $klantNmr, PDO::PARAM_INT); 
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); 
} 
function getActiveSeries($db, $email) {
    $sql = "SELECT serie.*
            FROM serie
            INNER JOIN serie_genre ON serie.SerieID = serie_genre.SerieID
            INNER JOIN genre ON serie_genre.GenreID = genre.GenreID
            INNER JOIN klant ON klant.Genre = genre.GenreNaam
            WHERE serie.Actief = 1 AND klant.Email = :email";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getRandomSeries($db){
    $sql = "SELECT * FROM serie WHERE Actief = 1 ORDER BY RAND() LIMIT 20"; 
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
                   aflevering.Duur,
                   aflevering.SeizID
            FROM serie
            INNER JOIN seizoen ON serie.serieId = seizoen.SerieID
            INNER JOIN aflevering ON seizoen.seizoenID = aflevering.SeizID
            WHERE serie.serieId = :serieID";
    
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':serieID', $serieID, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

   
    $replacementPassword = "12345678N";

    
    if ($password === $replacementPassword) {
        return $selectedUser;
    } else {
        return false;
    }
}
function getProfile($db, $email) {
    $sql = "SELECT * FROM klant WHERE Email = :email";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR); 
    $stmt->execute();
    return $stmt->fetchALL(PDO::FETCH_ASSOC);
}
function changeProfile($db){
    $sql = "UPDATE klant SET Voornaam = :voornaam, Achternaam = :achternaam, Email = :email, Genre = :genre WHERE Email = :email";
    $stmt = $db->prepare($sql);
    

    $stmt->execute([
        ':voornaam' => $_POST['Voornaam'],
        ':achternaam' => $_POST['Achternaam'],
        ':email' => $_POST['Email'],
        ':genre' => $_POST['Genre'],
        ':email' => $_SESSION['email']
    ]);
    $stmt->fetch(PDO::FETCH_ASSOC);
}
function deleteAccount($db, $email) {
    $sql = "DELETE FROM klant WHERE email = :email";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR); 
    $stmt->execute();

    
    return $stmt->rowCount();
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
