<?php
require_once('config.php');
class dbConfig {
    protected $conn;

    protected function connect() {
        $servername = "localhost:3306";
        $username = "root";
        $password = "Hoefblad89" ;
        $dbname = "hobo2022";

        try {
            $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Verbindingsfout: " . $e->getMessage();
            exit();
        }
    }
}
?>
