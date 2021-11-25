<?php
session_start();
require_once("connect.php");
//
$queryName = "`name`, ";
$name = "'Patryk',";
// $queryName = "";
// $name = "";
//

//
$queryCity = "`city`, ";
$city = "'Gliwice',";
$queryCity = "";
$city = "";
//

//
$queryAddress = "`address`, ";
$address = "'Polna 23',";
$queryAddress = "";
$address = "";
//

//
$queryPhone = "`phone`, ";
$phone = "'123456789',";
$queryPhone = "";
$phone = "";
//


$sth = $dbh->query("INSERT INTO `users`($queryName $queryCity $queryAddress $queryPhone `password`, `email`) VALUES ($name $city $address $phone 'pass','Test@gmail.com')");

header("Location: index.php");
