<?php

require_once('config.php');
$db = new PDO("mysql:host=".$dbHost."dbname=".$dbName, $dbUser, $dbPassword);
$db->exec('USE hobo2022;');
?>