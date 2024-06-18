<?php
require_once ('../connect/connect.php');
class User extends dbConfig {
    public function __construct() {
        $this->connect();
    }

    public function getKlantNrByEmail($email) {
        try{
        $sql = "SELECT klantNr FROM klant WHERE Email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['klantNr'];}
        catch (PDOException $e) {
            return false;
        }
    }

    public function checkPassword($email, $password) {
        try{
        $sql = "SELECT * FROM klant WHERE email = ?";
        $stm = $this->conn->prepare($sql);
        $stm->execute([$email]);
        $selectedUser = $stm->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $selectedUser['password'])) {
            return $selectedUser;
        }
        return false;}
        catch (PDOException $e) {
            return false;
        }
    }

    public function getProfile($email) {
        try{
        $sql = "SELECT * FROM klant WHERE Email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);}
        catch (PDOException $e) {
            return false;
        }
    }

    public function changeProfile() {
        try{
        $sql = "UPDATE klant SET Voornaam = :voornaam, Achternaam = :achternaam, Email = :email, Genre = :genre WHERE Email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':voornaam' => $_POST['Voornaam'],
            ':achternaam' => $_POST['Achternaam'],
            ':email' => $_POST['Email'],
            ':genre' => $_POST['Genre'],
            ':email' => $_SESSION['email']
        ]);}
        catch (PDOException $e) {
            return false;
        }
    }

    public function deleteAccount($email) {
        try{
        $sql = "DELETE FROM klant WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();}
        catch (PDOException $e) {
            return false;
        }
    }

    public function adminLogin($user, $password) {
        try{
        $sql = "SELECT * FROM users WHERE username = :username";
        $stm = $this->conn->prepare($sql);
        $stm->execute(["username" => $user]);
        $selectedUser = $stm->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $selectedUser["password"])) {
            return $selectedUser;
        } else {
            return false;
        }}  catch (PDOException $e) {
            return false;
        }
        

    }
    
    public function register($data) {
        try{
        $errors = [];

        if ($data['confirm_email'] !== $data['email']) {
            $errors['email'] = "email doesn't match";
        }
        if (strlen($data['password']) < 8) {
            $errors['password'] = "minimum length 8 required";
        } else if(!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $data['password']) || !preg_match('/[A-Z]/', $data['password']) || !preg_match('/[a-z]/', $data['password'])) {
            $errors['password'] = "Use atleast one special, one uppercase and one lowercase character";
        }
        if ($data['confirm_password'] !== $data['password']) {
            $errors['confirm_password'] = "passwords do not match";
        }

        if (count($errors) == 0) {
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            if (isset($data['tussenvoegsel'])) {
                $sql = 'INSERT INTO klant (Voornaam, Tussenvoegsel, Achternaam, Email, password, Genre, AboID)
                        VALUES (?, ?, ?, ?, ?, ?, 1);';
                $stm = $this->conn->prepare($sql);
                $stm->execute([
                    $data['firstname'], 
                    $data['tussenvoegsel'], 
                    $data['lastname'], 
                    $data['email'], 
                    $password, 
                    $data['fav_genre']
                ]);
            } else {
                $sql = 'INSERT INTO klant (Voornaam, Achternaam, Email, password, Genre, AboID)
                        VALUES (?, ?, ?, ?, ?, 1);';
                $stm = $this->conn->prepare($sql);
                $stm->execute([
                    $data['firstname'], 
                    $data['lastname'], 
                    $data['email'], 
                    $password, 
                    $data['fav_genre']
                ]);
            }

            $userId = $this->conn->lastInsertId();
            return [
                'success' => true,
                'user_id' => $userId,
                'errors' => []
            ];
        } else {
            return [
                'success' => false,
                'user_id' => null,
                'errors' => $errors
            ];
        }
    }catch (PDOException $e) {
        return false;
    }
}}
class Serie extends dbConfig{

    public function __construct() {
        $this->connect();
    }

    public function show($email) {
        try{
        $user = new User();
        $klantNr = $user->getKlantNrByEmail($email);

        $sql = "SELECT
            serie.SerieTitel,
            GROUP_CONCAT(DISTINCT aflevering.AfleveringID ORDER BY aflevering.AfleveringID) AS aflevering_ids,
            GROUP_CONCAT(DISTINCT aflevering.AflTitel ORDER BY aflevering.AflTitel) AS aflevering_titels,
            GROUP_CONCAT(DISTINCT seizoen.SeizoenID ORDER BY seizoen.SeizoenID) AS seizoen_ids,
            GROUP_CONCAT(DISTINCT seizoen.Rang ORDER BY seizoen.Rang) AS seizoenen_rangen,
            GROUP_CONCAT(stream.d_start ORDER BY stream.d_start) AS start_dates,
            GROUP_CONCAT(stream.d_eind ORDER BY stream.d_eind) AS end_dates
        FROM
            stream
        INNER JOIN
            aflevering ON stream.AflID = aflevering.AfleveringID
        INNER JOIN
            seizoen ON aflevering.SeizID = seizoen.SeizoenID
        INNER JOIN
            serie ON seizoen.SerieID = serie.SerieID
        WHERE
            stream.KlantID = :klantNr
        GROUP BY
            serie.SerieTitel
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':klantNr', $klantNr, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);}
        catch (PDOException $e) {
            return false;
        }
    }

    public function getActiveSeries($email) {
        try{
        $sql = "SELECT serie.*
                FROM serie
                INNER JOIN serie_genre ON serie.SerieID = serie_genre.SerieID
                INNER JOIN genre ON serie_genre.GenreID = genre.GenreID
                INNER JOIN klant ON klant.Genre = genre.GenreNaam
                WHERE serie.Actief = 1 AND klant.Email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);}
        catch (PDOException $e) {
            return false;
        }
    }

    public function getRandomSeries() {
        try{
        $sql = "SELECT * FROM serie WHERE Actief = 1 ORDER BY RAND() LIMIT 20";
        $result = $this->conn->query($sql);
        return $result->fetchAll(PDO::FETCH_ASSOC);}
        catch (PDOException $e) {
            return false;
        }
    }

    public function getSerieInfo($serieID, $SznID) {
        try{
        $sql = "SELECT serie.*, 
                       seizoen.Rang AS sRang,
                       seizoen.SeizoenID, 
                       seizoen.Jaar,
                       seizoen.IMDBRating, 
                       aflevering.Rang,
                       aflevering.afleveringID,
                       aflevering.AflTitel,
                       aflevering.Duur,
                       aflevering.SeizID
                FROM serie
                INNER JOIN seizoen ON serie.serieId = seizoen.SerieID
                INNER JOIN aflevering ON seizoen.seizoenID = aflevering.SeizID
                WHERE serie.serieId = :serieID AND seizoen.SeizoenID = :SznID";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':serieID', $serieID, PDO::PARAM_INT);
        $stmt->bindParam(':SznID', $SznID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);}
        catch (PDOException $e) {
            return false;
        }
    }

    public function getSeason($seasonID, $serieID) {
        try{
        $sql = "SELECT SeizoenID FROM seizoen WHERE Rang = :seasonID AND serieID = :serieID";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':seasonID', $seasonID, PDO::PARAM_INT);
        $stmt->bindParam(':serieID', $serieID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);}
        catch (PDOException $e) {
            return false;
        }
    }

    public function getRang($serieID) {
        try{
        $sql = "SELECT Rang FROM seizoen WHERE serieID = :serieID";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':serieID', $serieID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);}
        catch (PDOException $e) {
            return false;
        }
    }

    public function search($search) {
        try{
        $searchLike = "%" . $search . "%";
        $query = $this->conn->prepare("SELECT * FROM serie WHERE Actief = 1 AND SerieTitel LIKE :search LIMIT 50");
        $query->execute(["search" => $searchLike]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        usort($results, fn($a, $b) => levenshtein($search, $a["SerieTitel"]) <=> levenshtein($search, $b["SerieTitel"]));

        return $results;}
        catch (PDOException $e) {
            return false;
        }
    }

    public function adminSearch($search = false, $offset = 0) {
        try{
        if ($search != false) {
            $searchLike = "%" . $search . "%";
            $offset = $offset * 30;
            $query = $this->conn->prepare("SELECT *
            FROM serie
            INNER JOIN serie_genre ON serie.SerieID = serie_genre.SerieID
            INNER JOIN genre ON serie_genre.GenreID = genre.GenreID
            INNER JOIN klant ON klant.Genre = genre.GenreNaam
            WHERE SerieTitel LIKE ? ORDER BY SerieTitel LIMIT 30 offset $offset");
            $query->execute([$searchLike]);
        } else {
            $offset = $offset * 30;
            $query = $this->conn->prepare("SELECT *
            FROM serie
            INNER JOIN serie_genre ON serie.SerieID = serie_genre.SerieID
            INNER JOIN genre ON serie_genre.GenreID = genre.GenreID
            INNER JOIN klant ON klant.Genre = genre.GenreNaam
            ORDER BY SerieTitel LIMIT 30 offset $offset");
            $query->execute();
        }
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        usort($results, fn($a, $b) => levenshtein($search, $a["SerieTitel"]) <=> levenshtein($search, $b["SerieTitel"]));

        return $results;}
        catch (PDOException $e) {
            return false;
        }
    }

    public function activateSerie($serieId) {
        try{
        $serie = $this->getSerieById($serieId);
        if ($serie == false) {
            throw new Exception("No serie found where SerieId = " . $serieId, 2);
        }
        if (isset($serie["Actief"])) {
            if ($serie["Actief"] == 1) {
                throw new Exception("Serie already actief", 1);
            } else {
                try {
                    $sql = "UPDATE serie SET Actief = 1 WHERE SerieID = :id";
                    $stm = $this->conn->prepare($sql);
                    $stm->execute(["id" => $serieId]);
                    return true;
                } catch (\Throwable $th) {
                    throw new Exception("Failed to activate serie where SerieId = : " . $serieId, 3);
                }
            }
        }}catch (PDOException $e) {
            return false;
        }
    }

    public function deactivateSerie($serieId) {
        try{
        $serie = $this->getSerieById($serieId);
        if ($serie == false) {
            return new Error("No serie found where SerieId = " . $serieId, 2);
        }
        if (isset($serie["Actief"])) {
            if ($serie["Actief"] == 0) {
                return new Error("Serie already deactivated", 1);
            } else {
                try {
                    $sql = "UPDATE serie SET Actief = 0 WHERE SerieID = :id";
                    $stm = $this->conn->prepare($sql);
                    $stm->execute(["id" => $serieId]);
                    return true;
                } catch (\Throwable $th) {
                    return new Error("Failed to deactivate serie where SerieId = : " . $serieId, 3);
                }
            }
        }}catch (PDOException $e) {
            return false;
        }
    }

    public function getSerieById($serieId) {
        try{
        $sql = "SELECT * FROM serie WHERE SerieID = :id";
        $stm = $this->conn->prepare($sql);
        $stm->execute(['id' => $serieId]);
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return false;
        }
        return $result;}
    catch (PDOException $e) {
        return false;
    }
    }
}
class Stream extends dbConfig{
    public function __construct() {
        $this->connect();
    }
    public function aanbevolen($klantID){
        try{
        $sql = "SELECT DISTINCT seizoen.SerieID
        FROM stream
        INNER JOIN aflevering ON stream.AflID = aflevering.AfleveringID
        INNER JOIN seizoen ON aflevering.SeizID = seizoen.SeizoenID
        WHERE stream.KlantID = :klantID";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':klantID', $klantID, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);}
    catch (PDOException $e) {
    return false;
}
}
    

    public function watch($klantId, $aflId) {
        try{
        $sql = "SELECT Duur FROM aflevering WHERE AfleveringID = :aflId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':aflId', $aflId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result === false) {
            throw new Exception("AfleveringID does not exist");
        }
    
        $duur = $result['Duur'];
        $sql = "INSERT INTO stream (KlantID, AflID, d_start, d_eind) VALUES (:klantId, :aflId, NOW(), :d_eind)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':klantId', $klantId, PDO::PARAM_INT);
        $stmt->bindParam(':aflId', $aflId, PDO::PARAM_INT);
        $einde = date('Y-m-d H:i:s', strtotime('+' . $duur . ' minutes'));
        $stmt->bindParam(':d_eind', $einde, PDO::PARAM_STR);
        $stmt->execute();}
        catch (PDOException $e) {
            return false;
        }
    }

    public function totalWatchTime($klantId) {
        try{
        $sql = "SELECT SUM(TIMESTAMPDIFF(MINUTE, d_start, d_eind)) as total FROM stream WHERE KlantID = :klantId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':klantId', $klantId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];}
        catch (PDOException $e) {
            return false;
        }
    }
}
class Genre extends dbConfig {
    public function __construct() {
        $this->connect();
    }
    public function getAllGenres() {
        try{
        $sql = "SELECT GenreNaam FROM genre";
        $stm = $this->conn->prepare($sql);
        $stm->execute();    
        return $stm->fetchAll(PDO::FETCH_COLUMN);}
        catch (PDOException $e) {
            return false;
        }
    }
}