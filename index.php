<?php
require_once("connect.php");
require_once("paths.php");
require_once("shared_functions.php"); // w finalnej wersji można usunąć, na razie do testów alertów  
require_once("index_logic.php");

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
    <!-- Otwieranie formularza jeśli dane do rejestacji były błędne  -->
    <?php
    if (isset($_SESSION['v_register'])) {
        echo "<script> $(document).ready(function() { $('#singup_container').css('visibility', 'visible'); }); </script>";
        unset($_SESSION['v_register']);
    }
    ?>

    <div class="Main">
        <div class="Sidebar">
            <nav>
                <img src="logo.jpg" alt="Logo" width="140px" height="98px">
                <div class="logomen">
                    <h1>Restau<span class="fast-flicker">racja</span> u <span class="flicker">Mentzena</span></h1>
                </div>
                <ul class="nav-links">
                    <li onclick="document.body.scrollTop = document.documentElement.scrollTop = 0">Strona główna</li>
                    <li onclick="smoothScroll(document.getElementById('menu'))">Menu</li>
                    <li onclick="smoothScroll(document.getElementById('kontakt'))">Kontakt</li>
                    <li id="singin" style='<?php echo $displaySignIn ?>'>Logowanie</li>
                    <li id="singup" style='<?php echo $displaySignUp ?>'>Rejestracja</li>
                    <li id="logout" style='<?php echo $displayLogout ?>'>Wyloguj</li>
                    <!-- Jak mam zrobić, żeby to wylogowało/usunęło sesje ^ POMOCY! -->
                </ul>
            </nav>
        </div>
        <div class="absolute">
            <?php
            // echo SuccessMessageGenerator("działa"); //do testowania alertow 
            // echo SuccessMessageGenerator("działa"); //do testowania alertow
            // echo SuccessMessageGenerator("działa"); //do testowania alertow
            // echo ErrorMessageGenerator("nie działa"); //do testowania alertow
            if (isset($_SESSION['general_message'])) {
                echo $_SESSION['general_message'];
                unset($_SESSION['general_message']);
            }
            ?>
        </div>
        <div id="backgroundImg">
            <!-- <img src="pizzaBackground.jpg" alt="Logo" width="100%"> -->
        </div>

        <div class="MainPage">
            <h1 id="menu">Menu:</h1>
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
            <h1 id="kontakt">Kontakt</h1>
            <p> Godziny otwarcia restauracji: </p>
            <ul>
                <li>pon. 10:00-18:00</li>
                <li>wto. 10:00-18:00</li>
                <li>sr. 10:00-18:00</li>
                <li>czw. 10:00-18:00</li>
                <li>pt. 10:00-18:00</li>
                <li>sb. 10:00-20:00</li>
            </ul>
            <p onclick="showDiv()"> >>> Jak dojechac? *CLICK* <<< </p>
                    <div id="google" style="display:none"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1095.329220266166!2d18.678521863440235!3d50.28589039078352!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x471131030b062f91%3A0x3e9d8c258fb6d04c!2sDom%20Studencki%20Elektron!5e0!3m2!1spl!2spl!4v1638126762822!5m2!1spl!2spl" width=100% height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe></div>
        </div>
    </div>

    <div class="popup_container" id="singin_container">
        <div class="LogIn">
            <div class="close_button" id="close_singin"><img class="close_button_image" src="images/pizza_open.svg"></div>
            <h1>Logowanie:</h1>
            <form action='<?php echo $pSignInLogic ?>' method='POST' class='g-3 needs-validation' novalidate>
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

            <form action='<?php echo $pSignUpValidation ?>' method='POST' class='g-3'>
                <div class="col-md-12">
                    <label for="inputName" class="form-label">Imie</label>
                    <input type="text" class="form-control <?php echo $vName ?>" id="Name" name="name" placeholder="Jan" value=<?php echo "'$uName'" ?>>
                </div>

                <div class="col-md-12">
                    <label for="inputEmail" class="form-label">E-mail</label>
                    <input type="text" class="form-control <?php echo $vEmail ?>" id="Email" name="email" placeholder="Jan_Nowak@gmail.com" value=<?php echo "'$uEmail'" ?>>
                </div>

                <div class="col-md-12">
                    <label for="inputPassword" class="form-label">Hasło</label>
                    <input type="password" class="form-control <?php echo $vPassword ?>" id="Password" name="password" placeholder="********">

                </div>
                <div class="col-md-12">
                    <label for="inputPassword2" class="form-label">Powtórz hasło</label>
                    <input type="password" class="form-control <?php echo $vPassword2 ?>" id="inputPassword2" name="password2" placeholder="********">

                </div>
                <div class="col-md-12" style="margin-top: 15px">
                    <label for="inputCity" class="form-label">Miasto</label>
                    <select class="form-select" id="inputCity" name="city">
                        <option <?php $uDefault ?>>Wybierz</option>
                        <option value="Gliwice" <?php $uGliwice ?>>Gliwice</option>
                        <option value="Katowice" <?php $uKatowice ?>>Katowice</option>
                        <option value="Zabrze" <?php $uZabrze ?>>Zabrze</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label for="inputAdress" class="form-label">Adres</label>
                    <input type="text" class="form-control <?php echo $vAddress ?>" id="inputAdress" name="address" placeholder="Wiejska 4/6/8" value=<?php echo "'$uAddress'" ?>>

                </div>
                <div class="col-md-12">
                    <label for="inputTelephoneNumber" class="form-label">Numer telefonu</label>
                    <input type="text" maxlength="9" class="form-control <?php echo $vPhone ?>" id="inputTelephoneNumber" name="phone" placeholder="123654956" value=<?php echo "'$uPhone'" ?>>

                </div>
                <div class="col-12" style="padding: 20px">
                    <button class="btn btn-primary" type="submit">Zarejestruj</button>
                </div>
            </form>
        </div>
    </div>
    <!-- <script>
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
    </script> -->
</body>

</html>