<?php
session_start();
require_once "paths.php";
require_once "$pSharedFunctions";
if (!isset($_SESSION['user_email'])) {
    header("Location: $pHome");
    exit();
}
if (!isset($_POST['dodaj_zam'])) {
    header("Location: $pOrders");
    exit();
}
if (count($_SESSION['koszyk']) <= 0) {
    header("Location: $pOrders");
    $_SESSION['general_message'] = ErrorMessageGenerator("Dodaj coś do zamówienia");
    exit();
}
require_once "$pDbConnection";
$idUser = $_SESSION['user_id'];
// $tmpQuery = $dbh->prepare('SELECT idOrders FROM orders WHERE idUser= :idUser GROUP BY idOrders HAVING count(*) >= 1 ');
// $result = $tmpQuery->execute([$idUser]);
// $tmp = $tmpQuery->rowCount();

$sth = $dbh->query("SELECT MAX(idOrders) as orderCount FROM orders");
$idOrders = $sth->fetch()['orderCount'] + 1;

for ($i = 0; $i < count($_SESSION['koszyk']); $i = $i + 2) {
    $idProduct = $_SESSION['koszyk'][$i];
    $number = $_SESSION['koszyk'][$i + 1];
    $sql = "INSERT  INTO orders (idOrders,idUser, idProduct, number) VALUES(?,?,?,?)";
    $stmt = $dbh->prepare($sql);
    $result = $stmt->execute([$idOrders, $idUser, $idProduct, $number]);
}
unset($_SESSION['koszyk']);
header("Location: $pHome");
