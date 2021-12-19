<!DOCTYPE HTML>
<html>

<head>
  <title>Lista uzytkownikow</title>
  <link href="/images/dot_ico/users.ico" rel="icon" type="image/x-icon" />
  <style>
    body {
      background-color: rgba(0, 0, 0, 0.90) !important;
      color: white !important;
    }

    table tr td {
      color: white !important;
    }

    .center {
      color: white !important;
      text-align: center;
    }

    .center_form {
      display: flex;
      gap: 10px;
      justify-content: center;
    }

    .absolute {
      width: 80%;
      max-width: 800px;
      position: absolute;
      margin-left: auto;
      margin-right: auto;
      margin-top: 20px;
      left: 0;
      right: 0;
      text-align: center;
    }

    .tableUnderline {
      border-bottom: solid 4px !important;
    }

    .table>tbody>tr>td {
      vertical-align: middle;
    }
  </style>

  </style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</head>

<body>
  <div class='absolute'>
    <?php
    session_start();
    if (isset($_SESSION['general_message'])) {
      echo $_SESSION['general_message'];
      unset($_SESSION['general_message']);
    }
    ?>
  </div>
</body>

</html>

<?php

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
    require_once "print_table_functions.php";

    // Zamówienia w trakcie realizacji 
    $queryProducts = generateProductsQuery(" WHERE od.Status = 'W trakcie realizacji' ");
    try {
      $sth = $dbh->query($queryProducts);
    } catch (Exception $e) {
      $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania do bazy danych");
      $_SESSION['general_message'] .= ErrorMessageGenerator($e);
    }

    $queryDetails = generateDetailsQuery(" WHERE od.Status = 'W trakcie realizacji' ");
    try {
      $sthDetails = $dbh->query($queryDetails);
    } catch (Exception $e) {
      $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania trzeciego do bazy danych");
      $_SESSION['general_message'] .= ErrorMessageGenerator($e);
    }

    // Używane do rowspana w tabeli
    $queryCount = "SELECT idOrders, count(*) AS count FROM orders GROUP BY idOrders";
    try {
      $sthCount = $dbh->query($queryCount);
    } catch (Exception $e) {
      $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania drugiego do bazy danych");
      $_SESSION['general_message'] .= ErrorMessageGenerator($e);
    }

    echo "<a href='$pHome' style='text-decoration: none; color: white;'><h3 style='width: 127px;text-align: center;'> ←Powrót</h3></a>";

    echo "<h1 style='text-align: center;'>Zamówienia w trakcie realizacji</h1>";

    // Count jest robiony tylko raz ale używany we wszystkich tabelach do rowspana
    $arrCount = $sthCount->fetchAll();

    $arrData = $sth->fetchAll();

    printTable($arrData, $arrCount, $sthDetails, "employee_panel.php", true, "Zrealizowano", true, "Anulowano");
    // Zamówienia w trakcie realizacji koniec

    // Zamówienia zrealizowane
    $queryProducts = generateProductsQuery(" WHERE od.Status = 'Zrealizowano' ");
    try {
      $sth = $dbh->query($queryProducts);
    } catch (Exception $e) {
      $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania do bazy danych");
      $_SESSION['general_message'] .= ErrorMessageGenerator($e);
    }

    $queryDetails = generateDetailsQuery(" WHERE od.Status = 'Zrealizowano' ");
    try {
      $sthDetails = $dbh->query($queryDetails);
    } catch (Exception $e) {
      $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania trzeciego do bazy danych");
      $_SESSION['general_message'] .= ErrorMessageGenerator($e);
    }

    echo "<h1 style='text-align: center;'>Zamówienia zrealizowane</h1>";

    $arrData = $sth->fetchAll();

    printTable($arrData, $arrCount, $sthDetails);
    // Zamówienia zrealizowane koniec

    // Zamówienia anulowane
    $queryProducts = generateProductsQuery(" WHERE od.Status = 'Anulowano' ");
    try {
      $sth = $dbh->query($queryProducts);
    } catch (Exception $e) {
      $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania do bazy danych");
      $_SESSION['general_message'] .= ErrorMessageGenerator($e);
    }

    $queryDetails = generateDetailsQuery(" WHERE od.Status = 'Anulowano' ");
    try {
      $sthDetails = $dbh->query($queryDetails);
    } catch (Exception $e) {
      $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania trzeciego do bazy danych");
      $_SESSION['general_message'] .= ErrorMessageGenerator($e);
    }

    echo "<h1 style='text-align: center;'>Zamówienia anulowane</h1>";

    $arrData = $sth->fetchAll();

    printTable($arrData, $arrCount, $sthDetails);
    // Zamówienia anulowane koniec

  } else {
    header("Location: $pHome");
  }
} else {
  header("Location: $pHome");
}
?>