<?php
require_once ('../connect/connect.php');

function getActiveSeries($db, $email) {
    $sql = "SELECT  serie.*
            FROM serie
            INNER JOIN serie_genre ON serie.SerieID = serie_genre.SerieID
            INNER JOIN genre ON serie_genre.GenreID = genre.GenreID
            INNER JOIN klant ON klant.Genre = genre.GenreNaam
            WHERE serie.Actief = 1 AND klant.Email = :email";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_INT);
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
    if (password_verify($password, $selectedUser["password"])) {
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

