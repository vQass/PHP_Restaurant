<?php
session_start();
require_once "paths.php";
require_once "$pSharedFunctions";
if (!isset($_SESSION['user_email'])) {
    header("Location: $pHome");
    exit();
}
// To musiałem usunąć bo nie bedzię zasetowane po wprowadzeniu formularza z danymi zamówienia
// if (!isset($_POST['dodaj_zam'])) {
//     header("Location: $pOrders");
//     exit();
// }
if (!isset($_SESSION['idOrders'])) {
    header("Location: $pOrders");
    $_SESSION['general_message'] = ErrorMessageGenerator("Z jakiegos powodu nie przekazano id zamówienia");
    exit();
}

require_once "$pDbConnection";
$idUser = $_SESSION['user_id'];


$idOrders = $_SESSION['idOrders'];
unset($_SESSION['idOrders']);



for ($i = 0; $i < count($_SESSION['koszyk']); $i = $i + 2) {
    $idProduct = $_SESSION['koszyk'][$i];
    $number = $_SESSION['koszyk'][$i + 1];
    $sql = "INSERT  INTO orders (idOrders,idUser, idProduct, number) VALUES(?,?,?,?)";
    $stmt = $dbh->prepare($sql);
    $result = $stmt->execute([$idOrders, $idUser, $idProduct, $number]);
}
unset($_SESSION['koszyk']);

// Usuwanie danych do formularza danych zamówienia
unset($_SESSION['od_name']);
unset($_SESSION['od_city']);
unset($_SESSION['od_address']);
unset($_SESSION['od_phone']);
unset($_SESSION['od_code']);
// Usuwanie zmiennych do walidacji danych z zamówienia
unset($_SESSION['ve_name']);
unset($_SESSION['ve_city']);
unset($_SESSION['ve_address']);
unset($_SESSION['ve_phone']);
$_SESSION['general_message'] .= SuccessMessageGenerator("Dziękujemy za złożenie zamówienia!");


header("Location: $pHome");
