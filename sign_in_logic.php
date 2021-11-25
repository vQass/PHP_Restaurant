<?php
require_once("paths.php");

if ((!isset($_POST['login'])) || (!isset($_POST['pass']))) {
  header("Location: $pHome");
  exit();
}

session_start();
require_once($pDbConnection);

unset($_SESSION['error']);

try {
  $dbh = new PDO($arg1, $db_user, $db_password,  array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

  $login = $_POST['login'];
  $pass = $_POST['password'];

  // 
  $login = htmlentities($login, ENT_QUOTES, "UTF-8");
  //

  // sprawdzic pozniej nazwy tabel
  $sth = $dbh->prepare("SELECT * FROM users WHERE login = ?");
  //
  if ($sth->execute(array($login))) {
    $recordCount = $sth->rowCount();

    if ($recordCount == 1) {
      $row = $sth->fetch();

      // dodac email
      if (sha1($pass) == $pass['password']) {
        $_SESSION['u_id'] = $row['id'];
        $_SESSION['u_name'] = $row['name'];
        $_SESSION['u_surname'] = $row['surname'];
        $_SESSION['u_login'] = $row['login'];
        $_SESSION['u_age'] = $row['age'];
        $_SESSION['u_permission'] = $row['permission'];
        //$_SESSION['password'] = $row['password'];
        //$_SESSION['u_email'] = $row['email'];
        //$_SESSION['u_creation_date'] = $row['creation_date'];
        // Chyba trzeba zrobić coś w stylu isActive i zamiast usuwać użytkowników z bazy danych to zmieniać isActive na false

        header("Location: $pHome"); // zmienic pozniej
      } else {
        // Błędne hasło dla podanego loginu
        $_SESSION['error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
        header("Location: $pSignInView");
      }
    } else if ($recordCount < 0) {
      // Nie znaleziono podanego loginu w bazie danych
      $_SESSION['error'] = '<span style="color:red">Nieprawidłowy login!</span>';
      header("Location: $pSignInView");
    } else // recordCount > 1
    {
      // W bazie danych znaleziono więcej niż jedno wystąpienie podanego loginu. To nigdy nie powinno się wydarzyć
      $_SESSION['error'] = '<span style="color:red">W bazie danych znajduje się kilku użytkowników z takim loginem, skontaktuj się supportem!</span>';
      header("Location: $pSignInView");
    }
  }
} catch (PDOException $ex) {
  // Brak połączenia z bazą danych
  $_SESSION['error'] = '<span style="color:red">Nie nawiązano połączenia z bazą danych, prosimy spróbować później!</span>';
  header("Location: $pSignInView");
}
