<?php
require_once("paths.php");
$_SESSION['general_message'] = '<div class="alert alert-danger alert-dismissible fade show absolute transparent" role="alert">
AAAAAA
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';


if ((!isset($_POST['email'])) || (!isset($_POST['password']))) {
  header("Location: $pHome");
  exit();
}

session_start();

try {
  require_once($pDbConnection);

  $email = $_POST['email'];
  $pass = $_POST['password'];

  // sprawdzic pozniej nazwy tabel
  $sth = $dbh->prepare("SELECT * FROM users WHERE email = ?");
  //
  if ($sth->execute(array($email))) {
    $recordCount = $sth->rowCount();

    if ($recordCount == 1) {
      $row = $sth->fetch();

      if (sha1($pass) == $row['password']) {
        $_SESSION['u_id'] = $row['id'];
        $_SESSION['u_name'] = $row['name'];
        $_SESSION['u_permission'] = $row['permission'];
        $_SESSION['u_email'] = $row['email'];

        $_SESSION['general_message'] = '<div class="alert alert-success alert-dismissible fade show absolute transparent" role="alert">
        Witaj ' . $_SESSION['u_name'] . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';


        header("Location: $pHome"); // zmienic pozniej
      } else {
        // Błędne hasło dla podanego loginu

        $_SESSION['general_message'] = '<div class="alert alert-danger alert-dismissible fade show absolute transparent" role="alert">
        Nieprawidłowy login lub hasło!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';

        header("Location: $pSignInView");
      }
    } else if ($recordCount <= 0) {
      // Nie znaleziono podanego emailu w bazie danych
      $_SESSION['general_message'] = '<div class="alert alert-danger alert-dismissible fade show absolute transparent" role="alert">
      Nieprawidłowy email!
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';

      header("Location: $pSignInView");
    } else // recordCount > 1
    {
      // W bazie danych znaleziono więcej niż jedno wystąpienie podanego loginu. To nigdy nie powinno się wydarzyć
      $_SESSION['general_message'] = '<div class="alert alert-danger alert-dismissible fade show absolute transparent" role="alert">
      W bazie danych znajduje się kilku użytkowników z takim emailem, skontaktuj się supportem!
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
      header("Location: $pSignInView");
    }
  }
} catch (PDOException $ex) {
  // Brak połączenia z bazą danych
  $_SESSION['general_message'] = '<div class="alert alert-danger alert-dismissible fade show absolute transparent" role="alert">
  Nie nawiązano połączenia z bazą danych, prosimy spróbować później!
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  header("Location: $pSignInView");
}
