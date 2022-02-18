<?php
// dane do łączenia z bazą danych

$db_name = "restauracja";
$host = "localhost";
$db_type = "mysql";
$charset = "utf8mb4";

$arg1 = "$db_type:host=$host;dbname=$db_name;charset=$charset";
$db_user = "root";
$db_password = "";

$dbh = new PDO($arg1, $db_user, $db_password, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
