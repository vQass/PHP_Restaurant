<?php

$db_name = "pdo";
$host = "localhost";
$db_type = "mysql";
$charset = "utf8mb4";

$arg1 = "$db_type:host=$host;dbname=$db_name;charset=$charset";
$db_user = "root";
$db_password = "";
$params = array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
?>