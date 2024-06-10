<?php
require_once ('../connect/connect.php');
class User extends dbConfig {
    public function __construct() {
        $this->connect();
    }

    public function getKlantNrByEmail($email) {
        $sql = "SELECT klantNr FROM klant WHERE Email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['klantNr'];
    }

    public function checkPassword($email, $password) {
        $sql = "SELECT * FROM klant WHERE email = ?";
        $stm = $this->conn->prepare($sql);
        $stm->execute([$email]);
        $selectedUser = $stm->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $selectedUser['password'])) {
            return $selectedUser;
        }
        return false;
    }

    public function getProfile($email) {
        $sql = "SELECT * FROM klant WHERE Email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function changeProfile() {
        $sql = "UPDATE klant SET Voornaam = :voornaam, Achternaam = :achternaam, Email = :email, Genre = :genre WHERE Email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':voornaam' => $_POST['Voornaam'],
            ':achternaam' => $_POST['Achternaam'],
            ':email' => $_POST['Email'],
            ':genre' => $_POST['Genre'],
            ':email' => $_SESSION['email']
        ]);
    }

    public function deleteAccount($email) {
        $sql = "DELETE FROM klant WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function adminLogin($user, $password) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stm = $this->conn->prepare($sql);
        $stm->execute(["username" => $user]);
        $selectedUser = $stm->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $selectedUser["password"])) {
            return $selectedUser;
        } else {
            return false;
        }
    }
    
    public function register($data) {
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
    }
}
class Serie extends dbConfig{
    public function __construct() {
        $this->connect();
    }

    public function show($email) {
        $user = new User();
        $klantNr = $user->getKlantNrByEmail($email);

        $sql = "
        SELECT
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
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActiveSeries($email) {
        $sql = "SELECT serie.*
                FROM serie
                INNER JOIN serie_genre ON serie.SerieID = serie_genre.SerieID
                INNER JOIN genre ON serie_genre.GenreID = genre.GenreID
                INNER JOIN klant ON klant.Genre = genre.GenreNaam
                WHERE serie.Actief = 1 AND klant.Email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRandomSeries() {
        $sql = "SELECT * FROM serie WHERE Actief = 1 ORDER BY RAND() LIMIT 20";
        $result = $this->conn->query($sql);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSerieInfo($serieID, $SznID) {
        $sql = "SELECT serie.*, 
                       seizoen.Rang AS sRang,
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
                WHERE serie.serieId = :serieID AND seizoen.SeizoenID = :SznID";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':serieID', $serieID, PDO::PARAM_INT);
        $stmt->bindParam(':SznID', $SznID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSeason($seasonID, $serieID) {
        $sql = "SELECT SeizoenID FROM seizoen WHERE Rang = :seasonID AND serieID = :serieID";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':seasonID', $seasonID, PDO::PARAM_INT);
        $stmt->bindParam(':serieID', $serieID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRang($serieID) {
        $sql = "SELECT Rang FROM seizoen WHERE serieID = :serieID";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':serieID', $serieID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($search) {
        $searchLike = "%" . $search . "%";
        $query = $this->conn->prepare("SELECT * FROM serie WHERE Actief = 1 AND SerieTitel LIKE :search LIMIT 50");
        $query->execute(["search" => $searchLike]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        usort($results, fn($a, $b) => levenshtein($search, $a["SerieTitel"]) <=> levenshtein($search, $b["SerieTitel"]));

        return $results;
    }

    public function adminSearch($search = false, $offset = 0) {
        if ($search != false) {
            $searchLike = "%" . $search . "%";
            $offset = $offset * 30;
            $query = $this->conn->prepare("SELECT serie.*
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

        return $results;
    }

    public function activateSerie($serieId) {
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
        }
    }

    public function deactivateSerie($serieId) {
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
        }
    }

    public function getSerieById($serieId) {
        $sql = "SELECT * FROM serie WHERE SerieID = :id";
        $stm = $this->conn->prepare($sql);
        $stm->execute(['id' => $serieId]);
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return false;
        }
        return $result;
    }
}
class Stream extends dbConfig{
    public function __construct() {
        $this->connect();
    }

    public function watch($klantId, $aflId) {
        $sql = "SELECT Duur FROM aflevering WHERE AfleveringID = :aflId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':aflId', $aflId, PDO::PARAM_INT);
        $stmt->execute();
        $duur = $stmt->fetch(PDO::FETCH_ASSOC)['Duur'];
        $sql = "INSERT INTO stream (KlantID, AflID, d_start, d_eind) VALUES (:klantId, :aflId, NOW(), :d_eind)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':klantId', $klantId, PDO::PARAM_INT);
        $stmt->bindParam(":aflId", $aflId, PDO::PARAM_INT);
        $einde = date('Y-m-d H:i:s', strtotime('+' . $duur . ' minutes'));
        $stmt->bindParam(':d_eind', $einde, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function totalWatchTime($klantId) {
        $sql = "SELECT SUM(TIMESTAMPDIFF(MINUTE, d_start, d_eind)) as total FROM stream WHERE KlantID = :klantId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':klantId', $klantId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
class Genre extends dbConfig {
    public function __construct() {
        $this->connect();
    }
    public function getAllGenres() {
        $sql = "SELECT GenreNaam FROM genre";
        $stm = $this->conn->prepare($sql);
        $stm->execute();    
        return $stm->fetchAll(PDO::FETCH_COLUMN);
    }
}