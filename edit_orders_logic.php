<?php
session_start();
require_once("paths.php");
require_once($pSharedFunctions);
$dbConnected = true;
if (isset($_SESSION['user_permission']) && ($_SESSION['user_permission'] == "admin" || $_SESSION['user_permission'] == "employee")) {
    try {
        require_once "$pDbConnection";
    } catch (Exception $e) {
        $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas łączenia z bazą danych");
        $_SESSION['general_message'] .= ErrorMessageGenerator($e);
        $dbConnected = false;
    }

    if (isset($_GET['retPath'])) {
        $retPath = $_GET['retPath'];
    } else {
        $retPath = $pHome;
    }

    if ($dbConnected) {
        // Sprawdzanie czy przyciski zamówień zostały naciśnięte (anulowanie lub oznaczenie jaki zrealizowane)
        if (isset($_GET['status']) && isset($_POST['orderID'])) {
            $orderID = $_POST['orderID'];
            $status = $_GET['status'];
            try {
                if ($status == 'Anulowano' || $status == 'Zrealizowano' || $status == 'W trakcie realizacji') {
                    $sth = $dbh->query("UPDATE ordersdetails SET `status`='$status' WHERE idOrders = $orderID");
                    $_SESSION['general_message'] = SuccessMessageGenerator("Pomyślnie oznaczono zamówienie o id: $orderID jako: $status");
                } else {
                    $_SESSION['general_message'] = SuccessMessageGenerator("Błędny status w zapytaniu, status: $status");
                }
            } catch (Exception $e) {
                $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas edycji zamówienia o id: $orderID");
                $_SESSION['general_message'] .= ErrorMessageGenerator($e);
            }
        }
        header("Location: $retPath");
    } else { // Brak połączenia z bazą danych
        header("Location: $retPath");
    }
} else { // Brak uprawnień
    header("Location: $pHome");
}
