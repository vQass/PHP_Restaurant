<?php
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
    $id = $_POST['activate'];
    try {
        $dbh->query("UPDATE users SET `isActive`='1' WHERE id = $id");
        $_SESSION['general_message'] .= SuccessMessageGenerator("Pomyślnie aktywowano użytkownika!");
    } catch (Exception $e) {
        $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas edycji użytkownika");
        $_SESSION['general_message'] .= ErrorMessageGenerator($e);
    }
    header("Location: $pUsersList");
} else {
    header("Location: $pHome");
}
