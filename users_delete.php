<?php
session_start();
require_once('paths.php');
if (isset($_SESSION['user_permission']) && $_SESSION['user_permission'] == "admin") {
  require_once($pSharedFunctions);
  try {
    require_once($pDbConnection);
  } catch (Exception $e) {
    $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas łączenia z bazą danych");
    $_SESSION['general_message'] .= ErrorMessageGenerator($e);
    header("Location: $pUsersList");
  }

  $userID = $_POST['delete'];
  if ($_SESSION['user_id'] == $userID) {
    $_SESSION['general_message'] .= ErrorMessageGenerator("Administrator nie może usunąć swojego konta");
    header("Location: $pUsersList");
    exit();
  } else {
    $canRemove = true;

    $sth =  $dbh->query("SELECT permission FROM users WHERE id = $userID");
    $permission = $sth->fetch();

    if ($permission['permission'] == 'admin') {

      // Sprawdzanie czy jest wiecej administratorow niz jeden
      $sth =  $dbh->query("SELECT id FROM users WHERE permission = 'admin'");
      if ($sth->rowCount() < 2) {
        // Niby to nigdy nie powinno się stać bo sprawdzamy czy osoba usuwana nie jest osobą usuwającą a tylko admin może usuwać użytkowników
        $canRemove = false;
        $_SESSION['general_message'] .= ErrorMessageGenerator("Nie można usunąć ostatniego administratora!");
      }
    }

    if ($canRemove) {
      try {
        $dbh->query("UPDATE users SET isActive = 0 WHERE id = $userID");
        $_SESSION['general_message'] = SuccessMessageGenerator("Pomyślnie usunięto użytkownika!");
      } catch (Exception $e) {
        $_SESSION['general_message'] .= ErrorMessageGenerator("Błąd podczas uwania użytkownika");
        $_SESSION['general_message'] .= ErrorMessageGenerator($e);
      }
    }

    header("Location: $pUsersList");
    exit();
  }
} else {
  header("Location: $pHome");
}
