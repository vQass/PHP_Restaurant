<?php
session_start();
require_once "paths.php";
require_once($pSharedFunctions);

if (isset($_POST['email'])) {

  $data_valid = true;

  $passwordQuery == "";
  $cityQuery = "";
  $nameQuery = "";
  $addressQuery = "";
  $phoneQuery = "";
  $permissionQuery = "";


  // zamiast e_email można robić general_message ale może być ostry spam na ekranie przez to

  $id = $_SESSION['eu_idUsera'];

  // Walidacja haseł
  $password = $_POST['password'];
  if ($password != "") {
    if ((strlen($password) < 2) || (strlen($password) > 20)) {
      $data_valid = false;
      $_SESSION['e_password'] = "Podaj hasło!";
      $_SESSION['ve_password'] = 'is-invalid';
    } else {
      $_SESSION['ve_password'] = 'is-valid';
    }
  }

  if ($data_valid) {
    $password2 = $_POST['password2'];
    if ($password != $password2) {
      $data_valid = false;
      $_SESSION['e_password2'] = "Podano różne hasła!";
      $_SESSION['ve_password2'] = 'is-invalid';
    } else {
      if ($password != "") {
        $_SESSION['ve_password2'] = 'is-valid';
        $passwordQuery = ", `password`='$password'";
      }
    }
  }

  // Walidacja maila
  $email = $_POST['email'];
  $_SESSION['eu_email'] = $email; // do formularza rejestracji jako value
  if ((strlen($email) > 30)) {
    $data_valid = false;
    $_SESSION['ve_email'] = 'is-invalid';
    $_SESSION['e_email'] = "Podaj email!";
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $data_valid = false;
    $_SESSION['ve_email'] = 'is-invalid';
    $_SESSION['e_email'] = "Niepoprawny format emailu!";
  } else  $_SESSION['ve_email'] = 'is-valid';

  // Wartości opcjonalne!
  $city = $_POST['city'];
  if ($city == "Wybierz") {
    $city = "";
  } else {
    $_SESSION['eu_city'] = $city; // do formularza rejestracji jako value
    $cityQuery = "`city`='$city', ";
  }

  $name = $_POST['name'];
  if ($name != "") {
    $_SESSION['eu_name'] = $name; // do formularza rejestracji jako value
    if ((strlen($name) < 2) || (strlen($name) > 20)) {
      $data_valid = false;
      $_SESSION['e_name'] = "Podaj imie!";
      $_SESSION['ve_name'] = 'is-invalid';
    } else if (!ctype_alpha($name)) {
      $data_valid = false;
      $_SESSION['ve_name'] = 'is-invalid';
      $_SESSION['e_name'] = "Imię może składać się tylko z liter!";
    } else {
      $_SESSION['ve_name'] = 'is-valid';
      $nameQuery = "`name`='$name', ";
    }
  }

  $address = $_POST['address'];
  if ($address != "") {
    $_SESSION['eu_address'] = $address; // do formularza rejestracji jako value
    if ((strlen($address) < 3) || (strlen($address) > 20)) {
      $data_valid = false;
      $_SESSION['ve_address'] = 'is-invalid';
      $_SESSION['e_address'] = "Podaj adres!";
    } else {
      $_SESSION['ve_address'] = 'is-valid';
      $addressQuery = "`address`='$address', ";
    }
  }

  $phone = trim($_POST['phone']);
  if ($phone != "") {
    $_SESSION['eu_phone'] = $phone; // do formularza rejestracji jako value
    if (strlen($phone) != 9 && !preg_match("/^[0-9]$/", $phone)) {
      $data_valid = false;
      $_SESSION['ve_phone'] = 'is-invalid';
      $_SESSION['e_phone'] = "Numer telefonu musi kładać się z 9 cyfr!";
    } else {
      $_SESSION['ve_phone'] = 'is-valid';
      $phoneQuery = "`phone`='$phone', ";
    }
  }

  // Dodac walidacje uprawnień np admin nie może zmienić zmieniać swojego uprawnienia
  $permission = $_POST['permission'];
  $permissionQuery = "`permission`='$permission', ";

  if ($data_valid) {
    try {
      // Łączenie z bazą danych jeśli reszta danych jest poprawna
      require_once "connect.php";
    } catch (Exception $e) {

      $_SESSION['general_message'] .= ErrorMessageGenerator("Błąd serwera!");

      $data_valid = false;
    }

    try {
      $sth = $dbh->prepare("SELECT id FROM users WHERE email=?  AND id != $id");

      $sth->execute(array($email));
    } catch (Exception $e) {
      $_SESSION['general_message'] .= ErrorMessageGenerator("Błąd podczas sprawdzania ilości użytkowników o podanym emailu!");
      $_SESSION['general_message'] .= ErrorMessageGenerator($e);

      $data_valid = false;
    }

    if ($data_valid) {

      $recordCount = $sth->rowCount();

      if ($recordCount > 0) {
        $data_valid = false;
        $_SESSION['e_email'] = "Istnieje już użytkownik o takim emailu";
        $_SESSION['ve_email'] = "is-invalid";
      }
    }
  }


  if ($data_valid) {
    try {

      $password = sha1($password);
      // Dodawanie nowego użytkownika do bazy danych
      // żeby działał prepare i execute trzeba chyba po VALUES( zrobić to samo co wcześniej czyli $nameQuery2 = ":name"; a potem w execute $nameQuery3 = "':name' => $name, "
      // generalnie dużo zmiennych, dużo roboty ale do zrobienia

      // $sth = $dbh->prepare("INSERT INTO `users`($nameQuery $cityQuery $addressQuery $phoneQuery `password`, `email`) VALUES (:name, :city, :address, :phone, :password, :email)");
      // $sth->execute(array(':name' => $name,  ':city' => $city, ':address' => $address, ':phone' => $phone, ':password' => $password, ':email' => $email));

      $dbh->query("UPDATE users SET $nameQuery $permissionQuery $cityQuery $addressQuery $phoneQuery `email`='$email' $passwordQuery WHERE id = $id");

      unset($_SESSION['eu_idUsera']);
      unset($_SESSION['eu_name']);
      unset($_SESSION['eu_permission']);
      unset($_SESSION['eu_city']);
      unset($_SESSION['eu_address']);
      unset($_SESSION['eu_phone']);
      unset($_SESSION['eu_email']);

      // unset($_SESSION['ve_password']);
      // unset($_SESSION['ve_password2']);
      // unset($_SESSION['ve_email']);
      // unset($_SESSION['ve_name']);
      // unset($_SESSION['ve_city']);
      // unset($_SESSION['ve_address']);
      // unset($_SESSION['ve_phone']);
    } catch (Exception $e) {

      $_SESSION['general_message'] .= ErrorMessageGenerator("Błąd podczas edycji użytkownika");
      $_SESSION['general_message'] .= ErrorMessageGenerator("$e");

      header("Location: $pUsersEdit");
      exit();
    }

    $_SESSION['general_message'] .= SuccessMessageGenerator("Eydcja użytkownika zakończona pomyślnie");

    header("Location: $pUsersList");
    exit();
  }
  header("Location: $pUsersEdit");
  exit();
}
header("Location: $pHome");
