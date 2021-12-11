<?php
session_start();
require_once "paths.php";
require_once($pSharedFunctions);

if (isset($_POST['email'])) {

    $data_valid = true;

    $_SESSION['v_register'] = true;

    $cityQuery = "";
    $nameQuery = "";
    $addressQuery = "";
    $phoneQuery = "";

    // zamiast e_email można robić general_message ale może być ostry spam na ekranie przez to

    // Walidacja haseł
    $password = $_POST['password'];
    if ((strlen($password) < 2) || (strlen($password) > 20)) {
        $data_valid = false;
        $_SESSION['v_password'] = 'is-invalid';
    } else {
        $_SESSION['v_password'] = 'is-valid';
    }

    $password2 = $_POST['password2'];
    if ($password != $password2 || (strlen($password) < 2 || strlen($password) > 20)) {
        $data_valid = false;
        $_SESSION['v_password2'] = 'is-invalid';
    } else {
        $_SESSION['v_password2'] = 'is-valid';
    }

    // Walidacja maila
    $email = $_POST['email'];
    $_SESSION['u_email'] = $email; // do formularza rejestracji jako value
    if ((strlen($email) > 30)) {
        $data_valid = false;
        $_SESSION['v_email'] = 'is-invalid';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $data_valid = false;
        $_SESSION['v_email'] = 'is-invalid';
    } else  $_SESSION['v_email'] = 'is-valid';


    // Wartości opcjonalne!
    $city = $_POST['city'];
    if ($city == "Wybierz") {
        $city = "";
    } else {
        $_SESSION['u_city'] = $city; // do formularza rejestracji jako value
        $cityQuery = "`city`, ";
        $city = "'$city', ";
    }

    $name = $_POST['name'];
    if ($name != "") {
        $_SESSION['u_name'] = $name; // do formularza rejestracji jako value
        if ((strlen($name) < 2) || (strlen($name) > 20)) {
            $data_valid = false;
            $_SESSION['v_name'] = 'is-invalid';
        } else if (!ctype_alpha($name)) {
            $data_valid = false;
            $_SESSION['v_name'] = 'is-invalid';
        } else {
            $_SESSION['v_name'] = 'is-valid';
            $nameQuery = "`name`, ";
            $name = "'$name', ";
        }
    }

    $address = $_POST['address'];
    if ($address != "") {
        $_SESSION['u_address'] = $address; // do formularza rejestracji jako value
        if ((strlen($address) < 3) || (strlen($address) > 20)) {
            $data_valid = false;
            $_SESSION['v_address'] = 'is-invalid';
        } else {
            $_SESSION['v_address'] = 'is-valid';
            $addressQuery = "`address`, ";
            $address = "'$address', ";
        }
    }

    $phone = trim($_POST['phone']);
    if ($phone != "") {
        $_SESSION['u_phone'] = $phone; // do formularza rejestracji jako value
        if (strlen($phone) != 9 && !preg_match("/^[0-9]$/", $phone)) {
            $data_valid = false;
            $_SESSION['v_phone'] = 'is-invalid';
        } else {
            $_SESSION['v_phone'] = 'is-valid';
            $phoneQuery = "`phone`, ";
            $phone = "'$phone', ";
        }
    }

    if ($data_valid) {
        try {
            // Łączenie z bazą danych jeśli reszta danych jest poprawna
            require_once "connect.php";
        } catch (Exception $e) {

            $_SESSION['general_message'] .= ErrorMessageGenerator("Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!");

            $data_valid = false;
        }

        try {
            $sth = $dbh->prepare("SELECT id FROM users WHERE email=?");

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
                $_SESSION['v_email'] = "is-invalid";
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

            $sth = $dbh->query("INSERT INTO `users`($nameQuery $cityQuery $addressQuery $phoneQuery `password`, `email`) VALUES ($name $city $address $phone '$password','$email')");

            unset($_SESSION['v_register']);
            unset($_SESSION['u_email']);
            unset($_SESSION['u_name']);
            unset($_SESSION['u_city']);
            unset($_SESSION['u_address']);
            unset($_SESSION['u_phone']);

            unset($_SESSION['v_password']);
            unset($_SESSION['v_password2']);
            unset($_SESSION['v_email']);
            unset($_SESSION['v_name']);
            unset($_SESSION['v_city']);
            unset($_SESSION['v_address']);
            unset($_SESSION['v_phone']);
        } catch (Exception $e) {

            $_SESSION['general_message'] .= ErrorMessageGenerator("Błąd podczas dodawania użytkownika do bazy danych, prosimy o rejestrację w innym terminie.");
            $_SESSION['general_message'] .= ErrorMessageGenerator("$e");

            header("Location: $pSignInView");
            exit();
        }

        $_SESSION['general_message'] .= SuccessMessageGenerator("Dziękujemy za rejestrację! Można przystąpić do logowania");

        header("Location: $pSignInView");
        exit();
    }
}
header("Location: $pSignInView");
