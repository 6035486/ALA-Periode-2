<?php

require_once('connect/connect.php');
class wachtwoordreset extends dbConfig
{
public function __construct(String $password) {
    $this->connect();
    $password = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stm = $this->conn->prepare("UPDATE klant SET password = ? WHERE true = true;");
        $stm->execute([$password]);

    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
} 
}
new wachtwoordreset("password");
