<?php
session_start();
require_once "paths.php";

if (isset($_POST['email'])) {

    $data_valid = true;

    $password = $_POST['password'];
    if ((strlen($password) < 2) || (strlen($password) > 20)) {
        $data_valid = false;
        $_SESSION['e_password'] = "Podaj hasło!";
    }
    $password2 = $_POST['password2'];
    if ($password != $password2) {
        $data_valid = false;
        $_SESSION['e_password2'] = "Podano różne hasła!";
    }

    $email = $_POST['email'];
    if ((strlen($email) < 3) || (strlen($email) > 30)) {
        $data_valid = false;
        $_SESSION['e_email'] = "Podaj email!";
    }

    if (isset($_POST['name'])) {
        $name = $_POST['name'];
        if ((strlen($name) < 3) || (strlen($name) > 20)) {
            $data_valid = false;
            $_SESSION['e_name'] = "Podaj imie!";
        }
    }

    if (isset($_POST['address'])) {
        $address = $_POST['address'];
        if ((strlen($address) < 3) || (strlen($address) > 20)) {
            $data_valid = false;
            $_SESSION['e_address'] = "Podaj adres!";
        }
    }

    if (isset($_POST['phone'])) {

        $phone = $_POST['phone'];
        if ((strlen($phone) < 3) || (strlen($phone) > 20)) {
            $data_valid = false;
            $_SESSION['e_phone'] = "Podaj numer telefonu!";
        }
    }

    if ($data_valid) {
        try {
            // Łączenie z bazą danych jeśli reszta danych jest poprawna
            require_once "connect.php";
        } catch (Exception $e) {
            $_SESSION['general_message'] = '<div class="alert alert-danger alert-dismissible fade show absolute transparent" role="alert">
                        Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            header("Location: $pSignUpView");
            exit();
        }

        try {
            // sprawdzanie unikalności maila
            $sth = $dbh->prepare("SELECT id FROM users WHERE email= ?");

            $sth->execute(array($email));
        } catch (Exception $e) {
            $_SESSION['general_message'] = '<div class="alert alert-danger alert-dismissible fade show absolute transparent" role="alert">
                        Błąd podczas sprawdzania ilości użytkowników o podanym emailu!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            header("Location: $pSignUpView");
            exit();
        }

        $recordCount = $sth->rowCount();
        if ($recordCount > 0) {
            $data_valid = false;
            $_SESSION['e_email'] = "Istnieje już użytkownik o takim emailu";
        }
    }

    $_SESSION['dataValid'] = $data_valid;

    if ($data_valid) {

        try {

            // Dodawanie nowego użytkownika do bazy danych
            // $sth = $dbh->prepare("INSERT INTO users ($nameQuery password, email, $cityQuery $addressQuery $pho   neQuery) VALUES (:name, :password, :email, :city, :address, :phone)");
            //$sth->execute(array(':name' => $name, ':password' => $password, ':email' => $email, ':city' => $city, ':address' => $address, ':phone' => $phone));

            $password = sha1($password);

            $sth = $dbh->query("INSERT INTO users  VALUES (NULL, '$name', 'user', '$city', '$address', '$phone', '1', '$password', '$email')");
        } catch (Exception $e) {
            $_SESSION['general_message'] = '<div class="alert alert-danger alert-dismissible fade show absolute transparent" role="alert">
                            Błąd podczas dodawania użytkownika do bazy danych, prosimy o rejestrację w innym terminie.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';

            $_SESSION['general_message'] = '<div class="alert alert-danger alert-dismissible fade show absolute transparent" role="alert">
                        ' . $e . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            header("Location: $pSignInView");
            exit();
        }

        $_SESSION['general_message'] = '<div class="alert alert-success alert-dismissible fade show absolute transparent" role="alert">
                    Dziękujemy za rejestrację! Można przystąpić do logowania
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
        header("Location: $pSignInView");
        exit();
    }
}
header("Location: $pSignInView");
