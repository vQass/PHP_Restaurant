<?php
session_start();
require_once("connect.php");
require_once("paths.php");

?>



<html>

<head>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

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

        <?php

        if (isset($_SESSION['general_message'])) {
            echo $_SESSION['general_message'];
            unset($_SESSION['general_message']);
        }
        ?>


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
            <?php
            echo "<form action='$pSignInLogic' method='POST' class='g-3 needs-validation' novalidate>"
            ?>
            <div class="col-md-12 login_input">
                <label for="InputEmail" class="form-label">E-mail*</label>
                <input type="text" class="form-control" id="InputEmail" name="email">
            </div>
            <div class="col-md-12 login_input">
                <label for="InputPassword" class="form-label">Hasło*</label>
                <input type="password" class="form-control" id="InputPassword" name="password">
            </div>
            <div class="col-12" style="padding: 20px">
                <button class="btn btn-primary" type="submit">Zaloguj</button>
            </div>
            </form>
        </div>
    </div>

    <div class="popup_container" id="singup_container">
        <div class="Register">
            <div class="close_button" id="close_singup"><img class="close_button_image" src="images/pizza_open.svg"></div>
            <h1>Rejestracja:</h1>
            <?php
            echo "<form action='$pSignUpValidation' method='POST' class='g-3 needs-validation' novalidate>"
            ?>

            <div class="col-md-12">
                <label for="inputName" class="form-label">Imie</label>
                <input type="text" class="form-control" id="Name" name="name" placeholder="Jan" required>
            </div>
            <?php
            // if (isset($_SESSION['e_name'])) {
            //     echo '<div class="error">' . $_SESSION['e_name'] . '</div>';
            //     unset($_SESSION['e_name']);
            // }
            ?>
            <div class="col-md-12">
                <label for="inputEmail" class="form-label">E-mail</label>
                <input type="text" class="form-control" id="Email" name="email" placeholder="Jan_Nowak@gmail.com" required>
            </div>
            <?php
            // if (isset($_SESSION['e_email'])) {
            //     echo '<div class="error">' . $_SESSION['e_email'] . '</div>';
            //     unset($_SESSION['e_email']);
            // }
            ?>
            <div class="col-md-12">
                <label for="inputPassword" class="form-label">Hasło</label>
                <input type="password" class="form-control" id="Password" name="password" placeholder="********" required>
                <?php
                // if (isset($_SESSION['e_password'])) {
                //     echo '<div class="error">' . $_SESSION['e_password'] . '</div>';
                //     unset($_SESSION['e_password']);
                // }
                ?>
            </div>
            <div class="col-md-12">
                <label for="inputPassword2" class="form-label">Powtórz hasło</label>
                <input type="password" class="form-control" id="inputPassword2" name="password2" placeholder="********" required>
                <?php
                // if (isset($_SESSION['e_password2'])) {
                //     echo '<div class="error">' . $_SESSION['e_password2'] . '</div>';
                //     unset($_SESSION['e_password2']);
                // }
                ?>
            </div>
            <div class="col-md-12" style="margin-top: 15px">
                <label for="inputCity" class="form-label">Miasto</label>
                <select class="form-select" id="inputCity" name="city">
                    <option selected value="Gliwice">Gliwice</option>
                    <option value="Katowice">Katowice</option>
                    <option value="Zabrze">Zabrze</option>
                </select>
            </div>
            <div class="col-md-12">
                <label for="inputAdress" class="form-label">Adres</label>
                <input type="text" class="form-control" id="inputAdress" name="address" placeholder="Wiejska 4/6/8" required>
                <?php
                // if (isset($_SESSION['e_address'])) {
                //     echo '<div class="error">' . $_SESSION['e_address'] . '</div>';
                //     unset($_SESSION['e_address']);
                // }
                ?>
            </div>
            <div class="col-md-12">
                <label for="inputTelephoneNumber" class="form-label">Numer telefonu</label>
                <input type="text" maxlength="9" class="form-control" id="inputTelephoneNumber" name="phone" placeholder="123654956" required>
                <?php
                // if (isset($_SESSION['e_phone'])) {
                //     echo '<div class="error">' . $_SESSION['e_phone'] . '</div>';
                //     unset($_SESSION['e_phone']);
                // }
                ?>
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