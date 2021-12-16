<?php

session_start();
require_once("paths.php");
require_once("$pSharedFunctions");
if (isset($_SESSION['user_email'])) {

  // sprawdzanie czy koszyk nie jest pusty
  if (count($_SESSION['koszyk']) <= 0) {
    header("Location: $pOrders");
    $_SESSION['general_message'] = ErrorMessageGenerator("Dodaj coś do zamówienia");
    exit();
  }

  try {
    require_once "$pDbConnection";
  } catch (Exception $e) {
    $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas łączenia z bazą danych");
    $_SESSION['general_message'] .= ErrorMessageGenerator($e);
    header("Location: $pUsersList");
  }

  try {
    // Bierzemy id edytowanego użytkownika z posta i wyszukujemy go w bazie danych

    $id = $_SESSION['user_id'];
    $sth = $dbh->query("SELECT name, city, address, phone FROM users WHERE id = $id");

    $data = $sth->fetch();
    $_SESSION['od_idUsera'] = $id;
    if (!isset($_SESSION['od_name'])) {
      $_SESSION['od_name'] = $data['name'];
    }
    if (!isset($_SESSION['od_city'])) {
      $_SESSION['od_city'] = $data['city'];
    }
    if (!isset($_SESSION['od_address'])) {
      $_SESSION['od_address'] = $data['address'];
    }
    if (!isset($_SESSION['od_phone'])) {
      $_SESSION['od_phone'] = $data['phone'];
    }
  } catch (PDOException $e) {
    $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas pobierania informacji o użytkowniku");
    $_SESSION['general_message'] .= ErrorMessageGenerator($e);
    header("Location: $pHome");
    exit();
  }


  // do formularza edycji, zostawiamy w session ponieważ jeśli dane bedą nieprawidłowe to załadujemy je ponownie do formularza

  $name = $_SESSION['od_name'];

  $city = $_SESSION['od_city'];

  $address = $_SESSION['od_address'];

  $phone = $_SESSION['od_phone'];

  if (isset($_SESSION['od_code'])) {
    $discount = $_SESSION['od_code'];
  } else {
    $discount = "";
  }


  $katowice = "";
  $gliwice = "";
  $zabrze = "";
  if (isset($_SESSION['od_city'])) {
    if ($_SESSION['od_city'] == "Katowice") {
      $katowice = "selected";
    } else if ($_SESSION['od_city'] == "Gliwice") {
      $gliwice = "selected";
    } else if ($_SESSION['od_city'] == "Zabrze") {
      $zabrze = "selected";
    }
  } else {
    $katowice = "selected";
  }

  // Dane wymagane 
  // ve_zmienna - is-valid lub is-invalid - używane do sylizacji inputów

  if (isset($_SESSION['ve_name'])) {
    $veName = $_SESSION['ve_name'];
    unset($_SESSION['ve_name']);
  } else $veName = "";

  if (isset($_SESSION['ve_address'])) {
    $veAddress = $_SESSION['ve_address'];
    unset($_SESSION['ve_address']);
  } else $veAddress = "";

  if (isset($_SESSION['ve_phone'])) {
    $vePhone = $_SESSION['ve_phone'];
    unset($_SESSION['ve_phone']);
  } else $vePhone = "";

  // Uprawnienia też będą mogły być złe np jak admin będzie chaiał zmienić sobie uprawnienia, dodać później

} else {
  // jeśli nie ma uprawnień administratora
  header("Location: $pHome");
}
?>

<!DOCTYPE HTML>
<html lang="pl">

<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <style>
    body {
      background-color: rgba(0, 0, 0, 0.90);
      color: white;
    }

    .mb-3 {
      width: 30%;
      margin-left: auto;
      margin-right: auto;
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
  </style>
</head>

<body>

  <div class='absolute'>
    <?php
    if (isset($_SESSION['general_message'])) {
      echo $_SESSION['general_message'];
      unset($_SESSION['general_message']);
    }
    ?>
  </div>
  <div class="mb-3" style="margin-top: 40px;">
    <h1>Rejestracja:</h1>

    <form action='<?php echo $pOrdersDetailsValidation ?>' method='POST' class='g-3'>
      <div class="col-md-12">
        <label for="inputName" class="form-label">Imie</label>
        <input type="text" class="form-control <?php echo $veName ?>" id="Name" name="name" placeholder="Jan" value=<?php echo "'$name'" ?>>
      </div>

      <div class="col-md-12">
        <label for="inputCity" class="form-label">Miasto</label>
        <select class="form-select" id="inputCity" name="city">
          <option value="Gliwice" <?php echo $gliwice ?>>Gliwice</option>
          <option value="Katowice" <?php echo $katowice ?>>Katowice</option>
          <option value="Zabrze" <?php echo $zabrze ?>>Zabrze</option>
        </select>
      </div>
      <div class="col-md-12">
        <label for="inputAdress" class="form-label">Adres</label>
        <input type="text" class="form-control <?php echo $veAddress ?>" id="inputAdress" name="address" placeholder="Wiejska 4/6/8" value=<?php echo "'$address'" ?>>
      </div>
      <div class="col-md-12">
        <label for="inputPhoneNumber" class="form-label">Numer telefonu</label>
        <input type="text" maxlength="9" class="form-control <?php echo $vePhone ?>" id="inputPhoneNumber" name="phone" placeholder="123654956" value=<?php echo "'$phone'" ?>>
      </div>

      <div class="col-md-12">
        <label for="inputDiscountCode" class="form-label">Kod promocyjny</label>
        <input type="text" class="form-control <?php echo $veDiscount ?>" id="inputDiscountCode" name="code" placeholder="Brak" value=<?php echo "'$discount'" ?>>
      </div>
      <br>
      <div class="col-12">
        <button class="btn btn-primary" type="submit">Zatwierdź</button>
      </div>
    </form>
  </div>
</body>

</html>