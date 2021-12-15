<?php
@session_start();
require_once("paths.php");
require_once($pSharedFunctions);
$dbConnected = true;

if (isset($_SESSION['user_permission']) && $_SESSION['user_permission'] == "admin") {
  try {
    require_once "$pDbConnection";
  } catch (Exception $e) {
    $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas łączenia z bazą danych");
    $_SESSION['general_message'] .= ErrorMessageGenerator($e);
    $dbConnected = false;
  }

  if ($dbConnected) {

    if (isset($_POST['confirm'])) {
      $orderID = $_POST['confirm'];
      try {
        $sth = $dbh->query("UPDATE ordersdetails SET `status`='Zrealizowano' WHERE idOrders = $orderID");
        $_SESSION['general_message'] = SuccessMessageGenerator("Pomyślnie zrealizowano zamówienie");
      } catch (Exception $e) {
        $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas anulowania zamówienia");
        $_SESSION['general_message'] .= ErrorMessageGenerator($e);
      }
    }
    header("Location: $pUsersOrders");
  } else {
    header("Location: $pUsersList");
  }
} else {
  header("Location: $pHome");
}
