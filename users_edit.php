<?php

// na jutro: walidacja edycji, usuwanie uzytkownika, jak walidacja sie uda unset zmienne sesyjne, dodawanie do bazy, walidacje dodac przez require once, zrobic tam is valid is invalid 
session_start();
require_once("paths.php");
require_once("$pSharedFunctions");
if (isset($_SESSION['user_permission']) && $_SESSION['user_permission'] == "admin") {
  try {
    require_once "$pDbConnection";
  } catch (Exception $e) {
    $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas łączenia z bazą danych");
    $_SESSION['general_message'] .= ErrorMessageGenerator($e);
    header("Location: $pUsersList");
  }

  try {
    // Bierzemy id edytowanego użytkownika z posta i wyszukujemy go w bazie danych
    if (isset($_POST['edit'])) {
      $id = $_POST['edit'];
      $sth = $dbh->query("SELECT name, permission, city, address, phone, email FROM users WHERE id = $id");

      $data = $sth->fetch();
      $_SESSION['eu_idUsera'] = $id;
      $_SESSION['eu_name'] = $data['name'];
      $_SESSION['eu_permission'] = $data['permission'];
      $_SESSION['eu_city'] = $data['city'];
      $_SESSION['eu_address'] = $data['address'];
      $_SESSION['eu_phone'] = $data['phone'];
      $_SESSION['eu_email'] = $data['email'];
    }
  } catch (PDOException $e) {
    $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas pobierania informacji o użytkowniku");
    $_SESSION['general_message'] .= ErrorMessageGenerator($e);
  }


  // do formularza edycji, zostawiamy w session ponieważ jeśli dane bedą nieprawidłowe to załadujemy je ponownie do formularza
  if (isset($_SESSION['eu_name'])) {
    $name = $_SESSION['eu_name'];
  } else {
    $name = "";
  }
  if (isset($_SESSION['eu_city'])) {
    $city = $_SESSION['eu_city'];
  } else {
    $city = "";
  }
  if (isset($_SESSION['eu_address'])) {
    $address = $_SESSION['eu_address'];
  } else {
    $address = "";
  }
  if (isset($_SESSION['eu_phone'])) {
    $phone = $_SESSION['eu_phone'];
  } else {
    $phone = "";
  }
  if (isset($_SESSION['eu_email'])) {
    $email = $_SESSION['eu_email'];
  } else {
    $email = "";
  }

  $katowice = "";
  $gliwice = "";
  $zabrze = "";
  $default = "";
  if (isset($_SESSION['eu_city'])) {
    if ($_SESSION['eu_city'] == "Katowice") {
      $katowice = "selected";
    } else if ($_SESSION['eu_city'] == "Gliwice") {
      $gliwice = "selected";
    } else if ($_SESSION['eu_city'] == "Zabrze") {
      $zabrze = "selected";
    }
  } else {
    $user = "selected";
  }


  $user = "";
  $admin = "";
  $employee = "";
  if (isset($_SESSION['eu_permission'])) {
    if ($_SESSION['eu_permission'] == "user") {
      $user = "selected";
    } else if ($_SESSION['eu_permission'] == "admin") {
      $admin = "selected";
    } else {
      $employee = "selected";
    }
  } else {
    $user = "selected";
  }

  // Dane wymagane 
  // ve_zmienna - is-valid lub is-invalid - używane do sylizacji inputów
  if (isset($_SESSION['ve_email'])) {
    $veEmail = $_SESSION['ve_email'];
    unset($_SESSION['ve_email']);
  } else $veEmail = "";

  if (isset($_SESSION['ve_password'])) {
    $vePassword = $_SESSION['ve_password'];
    unset($_SESSION['ve_password']);
  } else $vePassword = "";

  if (isset($_SESSION['ve_password2'])) {
    $vePassword2 = $_SESSION['ve_password2'];
    unset($_SESSION['ve_password2']);
  } else $vePassword2 = "";

  // Dane opcjonalne
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
  header("Location: $pUsersList");
}
?>

<!DOCTYPE HTML>
<html lang="pl">

<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <style>
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

    <form action='<?php echo $pUsersEditValidation ?>' method='POST' class='g-3'>
      <div class="col-md-12">
        <label for="inputName" class="form-label">Imie</label>
        <input type="text" class="form-control <?php echo $veName ?>" id="Name" name="name" placeholder="Jan" value=<?php echo "'$name'" ?>>
      </div>

      <div class="col-md-12">
        <label for="inputEmail" class="form-label">E-mail</label>
        <input type="text" class="form-control <?php echo $veEmail ?>" id="Email" name="email" placeholder="Jan_Nowak@gmail.com" value=<?php echo "'$email'" ?>>
      </div>

      <div class="col-md-12">
        <label for="inputPassword" class="form-label">Hasło</label>
        <input type="password" class="form-control <?php echo $vePassword ?>" id="Password" name="password" placeholder="********">
      </div>

      <div class="col-md-12">
        <label for="inputPassword2" class="form-label">Powtórz hasło</label>
        <input type="password" class="form-control <?php echo $vePassword2 ?>" id="inputPassword2" name="password2" placeholder="********">
      </div>

      <div class="col-md-12">
        <label for="inputCity" class="form-label">Miasto</label>
        <select class="form-select" id="inputCity" name="city">
          <option <?php echo $default ?>>Wybierz</option>
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

      <label for="inputPermission" class="form-label">Uprawnienia</label>
      <select class="form-select" id="inputPermission" name="permission">
        <option value='user' <?php echo $user ?>>Użytkownik</option>
        <option value='employee' <?php echo $employee ?>>Pracownik</option>
        <option value='admin' <?php echo $admin ?>>Administator</option>
      </select><br />

      <div class="col-12">
        <button class="btn btn-primary" type="submit">Zatwierdź</button>
      </div>
    </form>
  </div>
</body>

</html>