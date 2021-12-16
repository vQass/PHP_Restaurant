<?php
session_start();
require_once "paths.php";
require_once($pSharedFunctions);

if (isset($_POST['name'])) {

  $data_valid = true;

  $id = $_SESSION['user_id'];

  // Wartości opcjonalne!
  $city = $_POST['city'];
  $_SESSION['od_city'] = $city; // do formularza rejestracji jako value

  $name = $_POST['name'];
  $_SESSION['od_name'] = $name; // do formularza rejestracji jako value
  if ((strlen($name) < 2) || (strlen($name) > 20)) {
    $data_valid = false;
    $_SESSION['ve_name'] = 'is-invalid';
  } else if (!ctype_alpha($name)) {
    $data_valid = false;
    $_SESSION['ve_name'] = 'is-invalid';
  } else {
    $_SESSION['ve_name'] = 'is-valid';
  }

  $address = $_POST['address'];
  $_SESSION['od_address'] = $address; // do formularza rejestracji jako value
  if ((strlen($address) < 3) || (strlen($address) > 20)) {
    $data_valid = false;
    $_SESSION['ve_address'] = 'is-invalid';
  } else {
    $_SESSION['ve_address'] = 'is-valid';
  }

  $phone = trim($_POST['phone']);
  $_SESSION['od_phone'] = $phone; // do formularza rejestracji jako value
  if (strlen($phone) != 9 && !preg_match("/^[0-9]$/", $phone)) {
    $data_valid = false;
    $_SESSION['ve_phone'] = 'is-invalid';
  } else {
    $_SESSION['ve_phone'] = 'is-valid';
  }

  if ($data_valid) {

    try {
      // Łączenie z bazą danych jeśli reszta danych jest poprawna
      require_once "connect.php";
    } catch (Exception $e) {

      $_SESSION['general_message'] .= ErrorMessageGenerator("Błąd serwera!");
      $data_valid = false;
    }
    // Pobrac kody promocyjne i sprawdzic czy wprowadzony kod jest poprwany, jak nie to kod ustawic na 'Brak'
    // W przyszłości można wrócić do zamówienia z informacją, że brak podanego kodu promocyjnego
    // Na ten moment nie ma na to czasu
    $code = $_POST['code'];
    $_SESSION['od_code'] = $code;

    $sth = $dbh->query("SELECT code FROM discounts");
    $foundCode = false;
    while ($row = $sth->fetch()) {
      if ($row['code'] == $code) {
        $foundCode = true;

        break;
      }
    }
    if (!$foundCode) {
      $code = "Brak";
    }

    $sth = $dbh->query("SELECT MAX(idOrders) as orderCount FROM orders");
    $idOrders = $sth->fetch()['orderCount'] + 1;

    $_SESSION['idOrders'] = $idOrders;

    try {
      // dodac rekord do tabeli pOrdersDetails
      $dbh->query("INSERT INTO `ordersdetails`(`idOrders`, `name`, `city`, `address`, `phone`, `discountCode`, `status`) VALUES ($idOrders,'$name','$city','$address','$phone','$code','W trakcie realizacji')");
    } catch (Exception $e) {

      $_SESSION['general_message'] .= ErrorMessageGenerator("Błąd podczas dodawania do bazy danych");
      $_SESSION['general_message'] .= ErrorMessageGenerator("$e");

      header("Location: $pOrdersDetails");
      exit();
    }


    header("Location: $pOrdersAdd");
    exit();
  }
  header("Location: $pOrdersDetails");
  exit();
}
header("Location: $pHome");
