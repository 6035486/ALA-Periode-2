<?php

include_once('config.php');
$db = new PDO("mysql:host=".$dbHost."dbname=".$dbName, $dbUser, $dbPassword);
$db->exec('USE '.$dbName.';');
