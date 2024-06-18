<?php
session_start();
require_once('../helpers/helpers.php');
if (!isset($_SESSION["admin"]) || $_SESSION["admin"] !== true) {
    header("Location: admin.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete']) && isset($_POST['email'])) {
    try {
        $user = new User();
        $count = $user->deleteAccount($_POST['email']);
        if ($count == 0) {
            
        }
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
    header("refresh:0");
    exit();
}

if (isset($_POST["page"])) {
    $page = (int)$_POST["page"];
} else {
    $page = 0;
}
class KlantBeheer extends dbConfig {
    /**
     * @param User[] $klanten
     */
    public $klanten = [];
    public function __construct($page) {
        $this->connect();
        try {
            $this->klanten = $this->klantBeheer($page);
            
        if (count($this->klanten) == 0) {
            $this->klanten = $this->klantBeheer($page - 1);
            throw new Exception("No more users");
        }   
        } catch (\Throwable $th) {
            echo $th;
        }
        
    }

    public function klantBeheer($page) {
        $sql = "SELECT * FROM klant LIMIT 40 OFFSET :offset";
        $offset = $page * 40;
        $stm = $this->conn->prepare($sql);
        $stm->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function search($search, $page) {
        $sql = "SELECT * FROM klant WHERE KlantNr LIKE :search OR Voornaam LIKE :search OR Achternaam LIKE :search OR Email LIKE :search LIMIT 40 OFFSET :offset";
        $stm = $this->conn->prepare($sql);
        $search = "%" . $search . "%";
        $offset = $page * 40;
        $stm->bindParam(':search', $search, PDO::PARAM_STR);
        $stm->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
$klantBeheer = new KlantBeheer($page);
if (isset($_POST['search'])) {
    $klantBeheer->klanten = $klantBeheer->search($_POST['search'], $page);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="admin">
    <header>
        <h1>Hobo Klanten beheer</h1>
        <a href="cms.php">cms</a>
    </header>
    <main>
        <section class="search">
            <form method="post">
                <input type="text" name="search" id="">
                <input type="submit" value="search">
                <input type="hidden" name="page" <?php echo 'value="'.$page.'"'; ?> id="">
            </form>
            <form method="post">
                <input type="submit" value="next" name="next">
                <input type="hidden" name="page" value="<?php echo $page + 1; ?>">
               
            </form>
            <?php if($page > 0) {
                    ?>
                    <form method="post">
                    <input type="submit" value="vorige" name="vorige">
                    <input type="hidden" name="page" value="<?php echo $page - 1; ?>">
                    </form>
                 
                    <?php
                }
                ?>
        </section>  
        <section class="users">
            <?php
                foreach ($klantBeheer->klanten as $klant) {
                    ?>
                        <article>
                            <p><?php echo $klant["KlantNr"] ?></p>
                            <p><?php echo $klant["Voornaam"] ?></p>
                            <p><?php echo $klant["Achternaam"] ?></p>
                            <p><?php echo $klant["Email"] ?></p>
                            <form method="post">
                            <input type="hidden" readonly value="<?php echo $klant['Email'] ?>" name="email">;
                            <input type="submit" name="delete" value="delete">
                            </form>

                        </article>
                    <?php }
                    ?>
        </section>
    </main>
</body>
</html>