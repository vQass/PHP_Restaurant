<?php

session_start();
require_once "paths.php";
require_once "$pSharedFunctions"; // w finalnej wersji można usunąć, na razie do testów alertów  
require_once "$pIndexLogic";


$dbConnected = true;
try {
    require_once "$pDbConnection";
} catch (Exception $e) {
    $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas łączenia z bazą danych");
    $_SESSION['general_message'] .= ErrorMessageGenerator($e);
    $dbConnected = false;
}

if ($dbConnected) {
    try {
        $menuQuery = $dbh->query('SELECT * FROM menu');
        $tmpQuery = $dbh->query('SELECT category FROM menu GROUP BY category HAVING count(*) >= 1');
        if (isset($_SESSION['user_email'])) {
            $basketQuerry = $dbh->query('SELECT m.name AS name, m.price AS price, o.number As number, d.discount AS dis
                                        FROM 
                                        ((orders o INNER JOIN menu m ON o.idProduct = m.id ) 
                                        INNER JOIN ordersdetails od ON o.idOrders = od.idOrders)
                                        INNER JOIN discounts d ON d.code = od.discountCode 
                                        WHERE o.idUser = ' . $_SESSION['user_id'] . ' and od.status = "W trakcie realizacji"');
            $basket = $basketQuerry->fetchAll();

            $basketQuerryS = $dbh->query('SELECT SUM(m.price * o.number * (1 - (d.discount / 100))) as summ
                                        FROM 
                                        ((orders o INNER JOIN menu m ON o.idProduct = m.id ) 
                                        INNER JOIN ordersdetails od ON o.idOrders = od.idOrders)
                                        INNER JOIN discounts d ON d.code = od.discountCode 
                                        WHERE o.idUser = ' . $_SESSION['user_id'] . ' AND od.status = "W trakcie realizacji"
                                        GROUP BY o.idUser');
            $basketS = $basketQuerryS->fetchAll();
        }
    } catch (Exception $e) {
        $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania do bazy danych");
        $_SESSION['general_message'] .= ErrorMessageGenerator($e);
    }
    $menu = $menuQuery->fetchAll();
    $tmp = $tmpQuery->fetchAll();
}
?>

<html>

<head>
    <link href="/images/dot_ico/pizza.ico" rel="icon" type="image/x-icon" />
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
    <title>Restauracja u Mentzena</title>

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
        <nav class="navbar navbar-expand-xxl navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php"><img id="logoImg" src="images/logo.jpg" alt="Logo"></a>
                <a class="navbar-brand logomen" href="index.php">Restau<span class="fast-flicker">racja</span> u <span class="flicker">Mentzena</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item" onclick="document.body.scrollTop = document.documentElement.scrollTop = 0" style='<?php echo $displayMain ?>'>
                            <a class="nav-link active" aria-current="page">Strona główna</a>
                        </li>
                        <li class="nav-item" id="menu_button" style='<?php echo $displayMenu ?>'>
                            <a class="nav-link">Menu</a>
                        </li>
                        <li class="nav-item" id="kontakt_button" style='<?php echo $displayKontakt ?>'>
                            <a class="nav-link">Kontakt</a>
                        </li>
                        <li class="nav-item" style='<?php echo $displayOrders ?>'>
                            <a href="<?php echo $pOrders ?>" class="nav-link">Złóż zamówienie</a>
                        </li>
                        <li class="nav-item" id="zamawianie" style='<?php echo $displayOrders ?>' onclick="showWheel()">
                            <a class="nav-link">Promocje</a>
                        </li>
                        <li class="nav-item" id="singin" style='<?php echo $displaySignIn ?>'>
                            <a class="nav-link">Logowanie</a>
                        </li>
                        <li class="nav-item" id="singup" style='<?php echo $displaySignUp ?>'>
                            <a class="nav-link">Rejestracja</a>
                        </li>
                        <li class="nav-item" id="logout" style='<?php echo $displayLogout ?>'>
                            <a href="<?php echo $pLogout ?>" class="nav-link">Wyloguj</a>
                        </li>
                        <li class="nav-item" style='<?php echo $displayAdminPanel ?>'>
                            <a href="<?php echo $pAdminPanel ?>" class="nav-link">Panel administratora</a>
                        </li>
                        <li class="nav-item" style='<?php echo $displayEmployeePanel ?>'>
                            <a href="<?php echo $pEmployeePanel ?>" class="nav-link">Panel pracownika</a>
                        </li>
                    </ul>
                    <ul style="display: none" class="navbar-nav ml-auto">
                        <button class="btn"></button>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="absolute">
            <?php
            if (isset($_SESSION['general_message'])) {
                echo $_SESSION['general_message'];
                unset($_SESSION['general_message']);
            }
            ?>
        </div>
        <div id="backgroundImg">
        </div>

        <h1 id="menu">Menu:</h1>
        <div class="MenuPage">
            <?php
            // chyba git nie mam innego pomysłu jak to zrobić
            foreach ($tmp as $kat) {
                $menuQuery = $dbh->prepare('SELECT * FROM menu WHERE category = :kategoria');
                $menuQuery->bindValue(':kategoria', $kat['category'], PDO::PARAM_STR);
                $menuQuery->execute();
                $menu = $menuQuery->fetchAll();
                echo "<h3>{$kat['category']}:</h3>";
                echo "<ul style='list-style-type: none'>";
                foreach ($menu as $temp) {
                    echo "<li style='float: left;padding: 10px;'>";
                    echo '<div class="ProductContainer">';
                    echo "<img class='ProductImage' src='Menu/{$temp['photo']}'>";
                    echo '<div class="ProductBG"></div>';
                    //echo "<input type='submit' class='ProductButton' value='+' name='{$temp['id']}'>";
                    echo "<div class='ProductPrice'> ";
                    echo number_format($temp['price'], 2);
                    echo "</div>";
                    echo "<div class='ProductName'> {$temp['name']}</div>";
                    echo "<div class='ProductInfoButton'>i";
                    echo "<div class='ProductInfo'>";
                    echo nl2br("{$temp['description']}");
                    echo "</div>";
                    echo "</div>";
                    echo "</li >";
                }
                echo "</ul>";
            }
            ?>

        </div>


        <div class="Contact">
            <h1 id="kontakt">Kontakt</h1>
            <p> Godziny otwarcia restauracji: </p>
            <ul>
                <li>pon. 10:00-18:00</li>
                <li>wto. 10:00-18:00</li>
                <li>sr. 10:00-18:00</li>
                <li>czw. 10:00-18:00</li>
                <li>pt. 10:00-21:00</li>
                <li>sb. 10:00-21:00</li>
            </ul>
            <p onclick="showDiv()" id="dojazd"> >>> Jak dojechac? *CLICK* <<< </p>
                    <div id="google" style="display:none">
                        <div class="car">
                            <img src="images/carAnimation/car.png">
                            <img src="images/carAnimation/wheel.png" class="back-wheel">
                            <img src="images/carAnimation/wheel.png" class="front-wheel">
                        </div>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1095.329220266166!2d18.678521863440235!3d50.28589039078352!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x471131030b062f91%3A0x3e9d8c258fb6d04c!2sDom%20Studencki%20Elektron!5e0!3m2!1spl!2spl!4v1638126762822!5m2!1spl!2spl" width=100% height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
        </div>

        <div class="footer">
            <div class="test"></div>
            <ul>
                <li><a href="https://www.facebook.com/slawomirmentzen" target="_blank"><img src="images/footer/facebook.png" alt="Facebook" id="fb"></a></li>
                <li><a href="https://twitter.com/SlawomirMentzen?ref_src=twsrc%5Egoogle%7Ctwcamp%5Eserp%7Ctwgr%5Eauthor" target="_blank"><img src="images/footer/twitter.png" alt="Twitter" id="tw"></a></li>
                <li><a href="https://youtu.be/njqEBOntE9I?t=27" target="_blank"><img src="images/footer/youtube.png" alt="Youtube" id="yt"></a></li>
                <li><a href="https://www.instagram.com/slawomirmentzen/?hl=pl" target="_blank"><img src="images/footer/instagram.png" alt="Instagram" id="insta"></a></li>
            </ul>
            <div id="copyright">Copyright © 2021</div>
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

    <div class="basket_button" id="basket_button_id" style='<?php echo $displayBasket ?>'>
        <img src="images/basket.svg" class="basket_image">
    </div>

    <div class="popup_container" id="basket_container">
        <div class="basket">
            <div class="close_button" id="close_basket"><img class="close_button_image" src="images/pizza_open.svg"></div>
            <h1>Zamówienie:</h1>
            <div class="orders">
                <?php
                // chyba git nie mam innego pomysłu jak to zrobić
                foreach ($basket as $order) {
                    echo "<div class='order'>";
                    echo "<div class='order_name'>" . $order['name'] . "</div>";
                    echo "<div class='order_price'>Cena: " . round(($order['price'] * (1 - ($order['dis'] / 100))), 2) . " zł</div>";
                    echo "<div class='order_count'>Szt.: " . $order['number'] . "</div>";
                    echo "</div>";
                    echo "<hr>";
                }

                echo '</div>';

                if (isset($basket[0])) {
                    echo '<div>Suma: ' . round($basketS[0]['summ'], 2) . 'zł</div>';
                }

                ?>

            </div>
        </div>

        <div class="spin">
            <h3 style="color: red;">Koło fortuny jest aktualnie w budowie! Przepraszamy za zainstaniałe problemy. Kody zniżkowe znajdują się na naszym fanpage'u!</h3>
            <div class="promocje">
                <button id="spin">Kręć!</button>
                <span class="arrow"></span>
                <div class="container">
                    <div class="one">Aktualne</div>
                    <div class="two">promocje</div>
                    <div class="three">są na</div>
                    <div class="four">naszej</div>
                    <div class="five">stronie</div>
                    <div class="six"> Za utrud</div>
                    <div class="seven">nienia prze</div>
                    <div class="eight">praszamy.</div>
                </div>
            </div>
        </div>
</body>

</html>