<?php
@session_start();
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

  if ($dbConnected) {

    // Sprawdzanie czy przyciski zamówień zostały naciśnięte (anulowanie lub oznaczenie jaki zrealizowane)
    if (isset($_POST['cancel'])) {
      $orderID = $_POST['cancel'];
      try {
        $sth = $dbh->query("UPDATE ordersdetails SET `status`='Anulowano' WHERE idOrders = $orderID");
        $_SESSION['general_message'] = SuccessMessageGenerator("Pomyślnie anulowano zamówienie");
      } catch (Exception $e) {
        $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas anulowania zamówienia");
        $_SESSION['general_message'] .= ErrorMessageGenerator($e);
      }
    }
    header("Location: $pEmployeePanel");
  } else {
    header("Location: $pEmployeePanel");
  }
} else {
  header("Location: $pHome");
}
