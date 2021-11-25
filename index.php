<?php
session_start();
require_once("connect.php");

if (isset($_POST['inputEmail'])) {

    $wszystko_OK = true;

    $email = $_POST['inputEmail'];
    $_SESSION['inputEmail'] = $_POST['inputEmail'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $wszystko_OK = false;
        $_SESSION['e_email'] = "Nieprawidlowy adres email!";
    }

    $haslo = $_POST['inputPassword'];
    $_SESSION['inputPassword'] = $_POST['inputPassword'];

    if ((strlen($haslo) < 2) || (strlen($haslo) > 20)) {
        $wszystko_OK = false;
        $_SESSION['e_haslo'] = "Podaj hasło!";
    }

    $wiek = $_POST['inputPassword2'];
    $_SESSION['inputPassword2'] = $_POST['inputPassword2'];

    if (($wiek < 18) || ($wiek > 50)) {
        $wszystko_OK = false;
        $_SESSION['e_inputPassword2'] = "Podaj hasło!";
    }

    $_SESSION['walidacjaOK'] = $wszystko_OK;
}

if (isset($_SESSION['walidacjaOK'])) {

    if ($_SESSION['walidacjaOK'] == true) {
        try {
            //$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
            $polaczenie = new PDO($arg1, $db_user, $db_password,  array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

            $email = $_SESSION['new_email'];
            $haslo = $_SESSION['new_password'];

            unset($_SESSION['walidacjaOK']);

            $haslo = sha1($haslo);

            echo $_SESSION['walidacjaOK'];

            if ($polaczenie->query("INSERT INTO user VALUES (NULL, '$imie', '$nazwisko', '$login', '$haslo', '$wiek', '$uprawnienia')")) {
                $_SESSION['udanarejestracja'] = true;
                header('Location: witamy.php');
            } else {
                throw new Exception($polaczenie->error);
            }
        } catch (Exception $e) {
            echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
            echo '<br />Informacja developerska: ' . $e;
        }
    } else {
        unset($_SESSION['walidacjaOK']);
    }
}

?>



<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="jquery.js"></script>
    <script src="index.js"></script>
</head>

<body>
    <div class="Main">
        <div class="Sidebar">
            <nav>
                <div class="Navbar">
                    <h1>Restauracja u Mentzena</h1>
                </div>
                <ul class="nav-links">
                    <li><a href="nic.html">Strona główna</a></li>
                    <li><a href="nic.html">Menu</a></li>
                    <li><a href="nic.html">Kontakt</a></li>
                    <li><a id="singin">Logowanie</a></li>
                    <li><a id="singup">Rejestracja</a></li>
                </ul>
            </nav>
        </div>


        <div class="MainPage">
            <h1>Menu:</h1>
            <ul>
                <li> Pizza 1</li>
                <li> Pizza 2</li>
                <li> Pizza 3</li>
                <li> Pizza 4</li>
                <li> Pizza 5</li>
                <li> Pizza 6</li>
                <li> Pizza 7</li>
                <li> Pizza 8</li>
                <li> Pizza 9</li>
                <li> Pizza 10</li>
            </ul>
        </div>


        <div class="Contact">
            <h1>Kontakt</h1>
            <p> Godziny otwarcia restauracji: </p>
            <ul>
                <li>pon. 10:00-18:00</li>
                <li>wto. 10:00-18:00</li>
                <li>sr. 10:00-18:00</li>
                <li>czw. 10:00-18:00</li>
                <li>pt. 10:00-18:00</li>
                <li>sb. 10:00-20:00</li>
            </ul>
            <h4>Dojazd</h4>
            <p>Tutaj będzie mapa google</p>
        </div>
    </div>

    <div class="popup_container" id="singin_container">
        <div class="LogIn">
            <div class="close_button" id="close_singin"><img class="close_button_image" src="images/pizza_open.svg"></div>
            <h1>Logowanie:</h1>
            <div class="col-md-12 login_input">
                <label for="InputEmail" class="form-label">E-mail*</label>
                <input type="text" class="form-control" id="InputEmail" name="InputEmail" required>
            </div>
            <div class="col-md-12 login_input">
                <label for="InputPassword" class="form-label">Hasło*</label>
                <input type="password" class="form-control" id="InputPassword" name="InputPassword" required>
            </div>
            <div class="col-12" style="padding: 20px">
                <button class="btn btn-primary" type="submit">Zaloguj</button>
            </div>
        </div>
    </div>

    <div class="popup_container" id="singup_container">
        <div class="Register">
            <div class="close_button" id="close_singup"><img class="close_button_image" src="images/pizza_open.svg"></div>
            <h1>Rejestracja:</h1>
            <form class="g-3 needs-validation" novalidate>
                <div class="col-md-12">
                    <label for="inputName" class="form-label">Imie</label>
                    <input type="text" class="form-control" id="inputName" name="inputName" placeholder="Jan">
                </div>
                <div class="col-md-12">
                    <label for="inputEmail" class="form-label">E-mail*</label>
                    <input type="text" class="form-control" id="inputEmail" name="inputEmail" placeholder="Jan_Nowak@gmail.com" required>
                </div>
                <div class="col-md-12">
                    <label for="inputPassword" class="form-label">Hasło*</label>
                    <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="********" required>
                </div>
                <div class="col-md-12">
                    <label for="inputPassword2" class="form-label">Powtórz hasło*</label>
                    <input type="password" class="form-control" id="inputPassword2" name="inputPassword2" placeholder="********" required>
                </div>
                <div class="col-md-12" style="margin-top: 15px">
                    <label for="inputCity" class="form-label">Miasto</label>
                    <select class="form-select" id="inputCity" name="inputCity">
                        <option selected disabled value="">Wybierz...</option>
                        <option>Gliwice</option>
                        <option>Katowice</option>
                        <option>Zabrze</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label for="inputAdress" class="form-label">Adres</label>
                    <input type="text" class="form-control" id="inputAdress" name="inputAdress" placeholder="Wiejska 4/6/8">
                </div>
                <div class="col-md-12">
                    <label for="inputTelephoneNumber" class="form-label">Numer telefonu</label>
                    <input type="text" maxlength="9" class="form-control" id="inputTelephoneNumber" name="inputTelephoneNumber" placeholder="123654956">
                </div>
                <div class="col-12" style="padding: 20px">
                    <button class="btn btn-primary" type="submit">Zarejestruj</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function() {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>

</html>