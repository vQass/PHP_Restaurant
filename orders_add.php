<?php
session_start();
require_once "paths.php";
if (!isset($_SESSION['user_email'])) {
    header("Location: $pHome");
    exit();
  }
if (!isset($_POST['dodaj_zam'])) {
    header("Location: $pOrders");
    exit();
}
require_once "$pDbConnection";
$idUser=$_SESSION['user_id'];
$tmpQuery = $dbh->prepare('SELECT idOrders FROM orders WHERE idUser= :idUser GROUP BY idOrders HAVING count(*) >= 1 ');
$result = $tmpQuery->execute([$idUser]);
$tmp = $tmpQuery->rowCount();

if($tmp==0){
    $idOrders=1;
}else $idOrders+=$tmp;

for($i=0;$i<count($_SESSION['koszyk']);$i=$i+2){
    $idProduct=$_SESSION['koszyk'][$i];
    $number=$_SESSION['koszyk'][$i+1];
    $sql ="INSERT  INTO orders (idOrders,idUser, idProduct, number) VALUES(?,?,?,?)";
    $stmt = $dbh->prepare($sql);
    $result = $stmt->execute([$idOrders,$idUser,$idProduct,$number]);
}
unset($_SESSION['koszyk']);
header("Location: $pHome");

