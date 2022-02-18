<?php
require_once("paths.php");

if ((!isset($_POST['email'])) || (!isset($_POST['password']))) {
  header("Location: $pHome");
  exit();
}

session_start();


try {
  require_once($pDbConnection);
  require_once($pSharedFunctions);

  $email = $_POST['email'];
  $pass = $_POST['password'];

  $sth = $dbh->prepare("SELECT id, email, password, permission FROM users WHERE email = ? AND isActive");

  if ($sth->execute(array($email))) {
    $recordCount = $sth->rowCount();

    if ($recordCount == 1) {
      $row = $sth->fetch();

      if (sha1($pass) == $row['password']) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_permission'] = $row['permission'];
        $_SESSION['user_email'] = $row['email'];

        $name = explode("@", $_SESSION['user_email'])[0];

        $_SESSION['general_message'] .= SuccessMessageGenerator("Witaj $name!");

        header("Location: $pHome");
      } else {

        // Błędne hasło dla podanego loginu

        $_SESSION['general_message'] = ErrorMessageGenerator("Nieprawidłowy login lub hasło!");

        header("Location: $pSignInView");
      }
    } else if ($recordCount <= 0) {
      // Nie znaleziono podanego emailu w bazie danych
      $_SESSION['general_message'] = ErrorMessageGenerator("Nieprawidłowy email!");

      header("Location: $pSignInView");
    } else // recordCount > 1
    {
      // W bazie danych znaleziono więcej niż jedno wystąpienie podanego loginu. To nigdy nie powinno się wydarzyć
      $_SESSION['general_message'] = ErrorMessageGenerator(" W bazie danych znajduje się kilku użytkowników z takim emailem, skontaktuj się supportem!");
      header("Location: $pSignInView");
    }
  }
} catch (PDOException $e) {
  // Brak połączenia z bazą danych
  $_SESSION['general_message'] = ErrorMessageGenerator("Nie nawiązano połączenia z bazą danych, prosimy spróbować później!");
  $_SESSION['general_message'] = ErrorMessageGenerator($e);
  header("Location: $pSignInView");
}
