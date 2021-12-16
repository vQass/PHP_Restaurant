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
    header("Location: $pMenu");
  }

  $id = $_POST['delete'];

  $sth =  $dbh->query("SELECT name FROM menu WHERE id = $id");
  $name = $sth -> fetch();
  $temp = true;
  if ($temp) {
      try {
        $stmt = $dbh->prepare('DELETE FROM menu WHERE id = :id');
	    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
	    $stmt->execute();
        $_SESSION['general_message'] = SuccessMessageGenerator("Pomyślnie usunięto {$name['name']}!");
      } catch (Exception $e) {
        $_SESSION['general_message'] .= ErrorMessageGenerator("Błąd podczas uwania użytkownika");
        $_SESSION['general_message'] .= ErrorMessageGenerator($e);
      }
      header("Location: $pMenu");
      exit();
    }
}else header("Location: $pHome");