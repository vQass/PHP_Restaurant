<!DOCTYPE HTML>
<html>

<head>
    <title>Lista uzytkownikow</title>
    <link href="/images/dot_ico/users.ico" rel="icon" type="image/x-icon" />
    <style>
        body {
            background-color: rgba(0, 0, 0, 0.95) !important;
            color: white !important;
        }

        table tr td {
            color: white !important;
        }

        .center {
            color: white !important;
            text-align: center;
        }

        .center_form {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .absolute {
            width: 80%;
            max-width: 800px;
            position: absolute;
            margin-left: auto;
            margin-right: auto;
            margin-top: 20px;
            left: 0;
            right: 0;
            text-align: center;
        }

        <style>.table>tbody>tr>td {
            vertical-align: middle;
        }
    </style>

    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</head>

<body>

</body>

</html>

<?php
@session_start();
require_once("paths.php");
require_once($pSharedFunctions);
$dbConnected = true;
if (isset($_SESSION['user_permission']) && $_SESSION['user_permission'] == "admin") {
    try {
        require_once "$pDbConnection";
    } catch (Exception $e) {
        $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas łączenia z bazą danych");
        $_SESSION['general_message'] .= ErrorMessageGenerator($e);
        $dbConnected = false;
    }

    // Wyświetlanie alertów
    echo "<div class='absolute'>";
    if (isset($_SESSION['general_message'])) {
        echo $_SESSION['general_message'];
        unset($_SESSION['general_message']);
    }
    echo "</div>";
    // 

    if ($dbConnected) {

        if (isset($_POST['orders'])) {
            $id = $_POST['orders'];
            $_SESSION['ueo_id'] = $id;
        } else {
            $id = $_session['ueo_id'];
        }

        // Zamówienia w trakcie realizacji

        // Wydaje mi się, że to zapytanie jest mniej optymalne od tych na dole xD
        // $queryProducts = "SELECT o.idOrders, m.name AS pizza, o.number, m.price * o.number * (1 - (d.discount/100)) AS price,
        //  od.name, od.city, od.address, od.phone, u.email, d.code FROM orders o
        //  INNER JOIN ordersdetails od ON od.idOrders = o.idOrders
        //  INNER JOIN users u ON o.idUser = u.id 
        //  INNER JOIN menu m ON o.idProduct = m.id 
        //  LEFT OUTER JOIN discounts d ON d.code = od.discountCode
        //  WHERE u.id = $id AND od.Status = 'W trakcie realizacji' ORDER BY o.idOrders";


        // Pobieram wszystkie nazwy produktów, ceny etc.
        $queryProducts = "SELECT o.idOrders, m.name AS pizza, o.number, m.price * o.number * (1 - (d.discount/100)) AS price FROM orders o
        INNER JOIN ordersdetails od ON od.idOrders = o.idOrders
        INNER JOIN menu m ON o.idProduct = m.id 
        LEFT OUTER JOIN discounts d ON d.code = od.discountCode
        WHERE o.idUser = $id AND od.Status = 'W trakcie realizacji' ORDER BY o.idOrders";

        // DISTINCT żeby pobrać tylko jeden wiersz, nie wiem, nie mam innego pomsyłu
        // Pobieram jeden wiersz dla każdego zamówienia do wyświetleni miasta etc.
        $queryDetails = "SELECT DISTINCT o.idOrders, od.discountCode, od.name, od.city, od.address, od.phone, u.email FROM ordersdetails od
         INNER JOIN orders o ON od.idOrders = o.idOrders
         INNER JOIN users u ON o.idUser = u.id
         WHERE o.idUser = $id AND od.Status = 'W trakcie realizacji' ORDER BY o.idOrders";

        // Używane do rowspana w tabeli
        $queryCount = "SELECT idOrders,  count(*) AS count FROM orders GROUP BY idOrders";

        try {
            $sth = $dbh->query($queryProducts);
        } catch (Exception $e) {
            $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania do bazy danych");
            $_SESSION['general_message'] .= ErrorMessageGenerator($e);
        }

        try {
            $sthCount = $dbh->query($queryCount);
        } catch (Exception $e) {
            $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania drugiego do bazy danych");
            $_SESSION['general_message'] .= ErrorMessageGenerator($e);
        }

        try {
            $sthDetails = $dbh->query($queryDetails);
        } catch (Exception $e) {
            $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania trzeciego do bazy danych");
            $_SESSION['general_message'] .= ErrorMessageGenerator($e);
        }

        echo "<a href='$pUsersList' style='text-decoration: none; color: white;'><h3 style='width: 127px;text-align: center;'> ←Powrót</h3></a>";
        echo "<h1 style='text-align: center;'>Zamówienia w trakcie realizacji</h1>";
        echo '<table class="center table " style="width: 80%; margin-left:auto; margin-right:auto">
            <tr>
                <th>Produkt</th>
                <th>Ilość</th>
                <th>Cena</th>
                <th>ID zam.</th>
                <th>Kod prom.</th>
                <th>Imię</th>
                <th>Miasto</th>
                <th>Adres</th>
                <th>Telefon</th>
                <th>Email</th>
                <th>Akcja</th>
            </tr>';
        // to na chwile do bazy danych, jesli dalej tu jest to znaczy, że zapomniałem, sorka
        // INSERT INTO `orders`(`idOrders`, `idUser`, `idProduct`, `price`, `status`, `number`, `city`, `address`, `phone`) VALUES (1,16,2,17,'W trakcie realizacji',1,'Gliwice', 'Polna 2', '123456789')

        $arrCount = $sthCount->fetchAll();
        $prevId = -1;

        while ($row = $sth->fetch()) {

            $count = 1;

            // Podpatrzyłem na necie xD 
            foreach ($arrCount as $key => $val) {
                if ($val['idOrders'] == $row['idOrders']) {
                    $count = $arrCount[$key]['count'];
                    break;
                }
            }

            echo "
            <tr >
                <td class='align-middle'>{$row['pizza']}</td>
                <td class='align-middle'>{$row['number']}</td>
                <td class='align-middle'>{$row['price']}</td>

                ";
            if ($prevId != $row['idOrders']) {
                $prevId = $row['idOrders'];

                $rowDetails = $sthDetails->fetch();
                echo "
                <td rowspan='$count'class='align-middle'>{$row['idOrders']}</td>
                <td rowspan='$count'class='align-middle'>{$rowDetails['discountCode']}</td>
                <td rowspan='$count'class='align-middle'>{$rowDetails['name']}</td>
                <td rowspan='$count'class='align-middle'>{$rowDetails['city']}</td>
                <td rowspan='$count'class='align-middle'>{$rowDetails['address']}</td>
                <td rowspan='$count'class='align-middle'>{$rowDetails['phone']}</td>
                <td rowspan='$count'class='align-middle'>{$rowDetails['email']}</td>
                
                    <td rowspan='$count' class='align-middle'>
                    
                    <form method='post' action='$pUsersOrders'> 
                    <button name='delete' class='btn btn-outline-danger align-middle' style='width: 50px' type='submit' value='{$row['idOrders']}'>✘</button>
                    </form> 
                    
                    <form method='post' action ='$pUsersOrders'> 
                    <button name='orders' class='btn btn-outline-success align-middle' style='width: 50px' type='submit' value='{$row['idOrders']}'>✔</button>
                    </form>  
                    </td>";
            }
            echo "</tr>";
        }
        echo '</table>';
        // Zamówienia w trakcie realizacji koniec

        // Użytkownicy nieaktywni 
        try {
            $sth = $dbh->query("SELECT id, name, permission, city, address, phone, email FROM users WHERE NOT isActive");
        } catch (Exception $e) {
            $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania do bazy danych");
            $_SESSION['general_message'] .= ErrorMessageGenerator($e);
        }
        echo "<h1 style='text-align: center;'>Użytkownicy nieaktywni</h1>"; //<table class="center table table-striped table-hover"
        echo '<table class="center table" style="width: 80%; margin-left:auto; margin-right:auto"> 
            <tr>
                <th>ID</th>
                <th>Imię</th>
                <th>Uprawnienia</th>
                <th>Miasto</th>
                <th>Adres</th>
                <th>Numer telefonu</th>
                <th>Email</th>
                <th>Akcja</th>
            </tr>';
        while ($row = $sth->fetch()) {
            // jeśli w bazie jest null to wpisujemy do zmiennej napis NULL, inaczej jest puste pole w tabeli. Jeśli różne od NULL przypisujemy wartość z bazy
            if ($row['name'] == NULL) {
                $name = "NULL";
            } else {
                $name = $row['name'];
            }
            if ($row['city'] == NULL) {
                $city = "NULL";
            } else {
                $city = $row['city'];
            }
            if ($row['address'] == NULL) {
                $address = "NULL";
            } else {
                $address = $row['address'];
            }
            if ($row['phone'] == NULL) {
                $phone = "NULL";
            } else {
                $phone = $row['phone'];
            }

            echo "
            <tr>
                <td>{$row['id']}</td>
                <td>{$name}</td>
                <td>{$row['permission']}</td>
                <td>{$city}</td>
                <td>{$address}</td>
                <td>{$phone}</td>
                <td>{$row['email']}</td>
                <td class='center_form'>
                    <form method='post' action ='$pUsersActivate'> 
                        <button name='activate' class='btn btn-outline-primary' type='submit' value='{$row['id']}'>Aktywuj</button>
                    </form>
                </td>
            </tr>";
        }
        echo '</table>';
        // Użytkownicy nieaktywni koniec

    } else {
        header("Location: $pHome");
    }
} else {
    header("Location: $pHome");
}
?>