<!DOCTYPE HTML>
<html>

<head>
  <title>Lista uzytkownikow</title>
  <link href="/images/dot_ico/users.ico" rel="icon" type="image/x-icon" />
  <style>
    body {
      background-color: rgba(0, 0, 0, 0.90) !important;
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

    .table>tbody>tr>td {
      vertical-align: middle;
    }
  </style>

  </style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</head>

<body>
  <div class='absolute'>
    <?php
    session_start();
    if (isset($_SESSION['general_message'])) {
      echo $_SESSION['general_message'];
      unset($_SESSION['general_message']);
    }
    ?>
  </div>
</body>

</html>

<?php

require_once("paths.php");
require_once($pSharedFunctions);
$dbConnected = true;
if (isset($_SESSION['user_permission']) && ($_SESSION['user_permission'] == "admin" || $_SESSION['user_permission'] == "employee")) {
  try {
    require_once "$pDbConnection";
  } catch (Exception $e) {
    $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas łączenia z bazą danych");
    $_SESSION['general_message'] .= ErrorMessageGenerator($e);
    $dbConnected = false;
  }

  if ($dbConnected) {

    // Trzy podobne tabele, można spróbować zrobić je w funkcji jak będzie czas

    // Pobieram wszystkie nazwy produktów, ceny etc.
    $queryProducts = "SELECT o.idOrders, m.name AS pizza, o.number, m.price * o.number * (1 - (d.discount/100)) AS price FROM orders o
        INNER JOIN ordersdetails od ON od.idOrders = o.idOrders
        INNER JOIN menu m ON o.idProduct = m.id 
        LEFT OUTER JOIN discounts d ON d.code = od.discountCode
        WHERE od.Status = 'W trakcie realizacji' ORDER BY o.idOrders";

    // DISTINCT żeby pobrać tylko jeden wiersz, nie wiem, nie mam innego pomsyłu
    // Pobieram jeden wiersz dla każdego zamówienia do wyświetleni miasta etc.
    $queryDetails = "SELECT DISTINCT o.idOrders, od.discountCode, od.name, od.city, od.address, od.phone, u.email FROM ordersdetails od
         INNER JOIN orders o ON od.idOrders = o.idOrders
         INNER JOIN users u ON o.idUser = u.id
         WHERE od.Status = 'W trakcie realizacji' ORDER BY o.idOrders";

    // Używane do rowspana w tabeli
    $queryCount = "SELECT idOrders, count(*) AS count FROM orders GROUP BY idOrders";

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

    echo "<a href='$pHome' style='text-decoration: none; color: white;'><h3 style='width: 127px;text-align: center;'> ←Powrót</h3></a>";
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
    $sum = 0;
    $i = 1; // Do wyświetlenia podsumowania zamówienia
    while ($row = $sth->fetch()) {

      $count = 1;

      // Podpatrzyłem na necie xD 
      foreach ($arrCount as $key => $val) {
        if ($val['idOrders'] == $row['idOrders']) {
          $count = $arrCount[$key]['count'] + 1;
          break;
        }
      }
      $price = number_format($row['price'], 2);

      echo "
            <tr >
                <td class='align-middle'>{$row['pizza']}</td>
                <td class='align-middle'>{$row['number']}</td>
                <td class='align-middle'>$price zł</td>
                ";

      $sum += $row['price'];

      if ($prevId != $row['idOrders']) {
        $prevId = $row['idOrders'];

        // czerwony 

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
                    
                    <form method='post' action='$pEmployeePanelCancel'> 
                    <button name='cancel' class='btn btn-outline-danger align-middle' style='width: 50px' type='submit' value='{$row['idOrders']}'>✘</button>
                    </form> 
                    
                    <form method='post' action='$pEmployeePanelConfirm'> 
                    <button name='confirm' class='btn btn-outline-success align-middle' style='width: 50px' type='submit' value='{$row['idOrders']}'>✔</button>
                    </form>  
                    </td>";
      }
      echo "</tr>";
      $i++;
      if ($i == $count) {
        $sum = number_format($sum, 2);
        echo " <td class='align-middle' ><h4>Suma:</h4> </td> ";
        echo " <td colspan='2'class='align-middle' ><h4>$sum zł</h4> </td> ";
        $sum = 0;
        $i = 1;
      }
    }
    echo '</table>';
    // Zamówienia w trakcie realizacji koniec


    // Zamówienia zrealizowane

    // Pobieram wszystkie nazwy produktów, ceny etc.
    $queryProducts = "SELECT o.idOrders, m.name AS pizza, o.number, m.price * o.number * (1 - (d.discount/100)) AS price FROM orders o
        INNER JOIN ordersdetails od ON od.idOrders = o.idOrders
        INNER JOIN menu m ON o.idProduct = m.id 
        LEFT OUTER JOIN discounts d ON d.code = od.discountCode
        WHERE od.Status = 'Zrealizowano' ORDER BY o.idOrders";

    // DISTINCT żeby pobrać tylko jeden wiersz, nie wiem, nie mam innego pomsyłu
    // Pobieram jeden wiersz dla każdego zamówienia do wyświetleni miasta etc.
    $queryDetails = "SELECT DISTINCT o.idOrders, od.discountCode, od.name, od.city, od.address, od.phone, u.email FROM ordersdetails od
         INNER JOIN orders o ON od.idOrders = o.idOrders
         INNER JOIN users u ON o.idUser = u.id
         WHERE od.Status = 'Zrealizowano' ORDER BY o.idOrders";

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

    echo "<h1 style='text-align: center;'>Zamówienia zrealizowane</h1>";
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
            </tr>';

    $prevId = -1;
    $sum = 0; // Do wyświetlenia podsumowania zamówienia
    $i = 1; // Do wyświetlenia podsumowania zamówienia
    while ($row = $sth->fetch()) {

      $count = 1;

      foreach ($arrCount as $key => $val) {
        if ($val['idOrders'] == $row['idOrders']) {
          $count = $arrCount[$key]['count'] + 1;
          break;
        }
      }
      $price = number_format($row['price'], 2);

      echo "
            <tr >
                <td class='align-middle'>{$row['pizza']}</td>
                <td class='align-middle'>{$row['number']}</td>
                <td class='align-middle'>$price zł</td>
                ";

      $sum += $row['price'];

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
";
      }
      echo "</tr>";
      $i++;
      if ($i == $count) {
        $sum = number_format($sum, 2);
        echo " <td class='align-middle' ><h4>Suma:</h4> </td> ";
        echo " <td colspan='2'class='align-middle' ><h4>$sum zł</h4> </td> ";
        $sum = 0;
        $i = 1;
      }
    }
    echo '</table>';
    // Zamówienia zrealizowane koniec

    // Zamówienia anulowane

    // Pobieram wszystkie nazwy produktów, ceny etc.
    $queryProducts = "SELECT o.idOrders, m.name AS pizza, o.number, m.price * o.number * (1 - (d.discount/100)) AS price FROM orders o
        INNER JOIN ordersdetails od ON od.idOrders = o.idOrders
        INNER JOIN menu m ON o.idProduct = m.id 
        LEFT OUTER JOIN discounts d ON d.code = od.discountCode
        WHERE od.Status = 'Anulowano' ORDER BY o.idOrders";

    // DISTINCT żeby pobrać tylko jeden wiersz, nie wiem, nie mam innego pomsyłu
    // Pobieram jeden wiersz dla każdego zamówienia do wyświetleni miasta etc.
    $queryDetails = "SELECT DISTINCT o.idOrders, od.discountCode, od.name, od.city, od.address, od.phone, u.email FROM ordersdetails od
         INNER JOIN orders o ON od.idOrders = o.idOrders
         INNER JOIN users u ON o.idUser = u.id
         WHERE od.Status = 'Anulowano' ORDER BY o.idOrders";

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

    echo "<h1 style='text-align: center;'>Zamówienia anulowane</h1>";
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
            </tr>';

    $prevId = -1;
    $sum = 0; // Do wyświetlenia podsumowania zamówienia
    $i = 1; // Do wyświetlenia podsumowania zamówienia
    while ($row = $sth->fetch()) {

      $count = 1;

      foreach ($arrCount as $key => $val) {
        if ($val['idOrders'] == $row['idOrders']) {
          $count = $arrCount[$key]['count'] + 1;
          break;
        }
      }

      $price = number_format($row['price'], 2);
      echo "
            <tr >
                <td class='align-middle'>{$row['pizza']}</td>
                <td class='align-middle'>{$row['number']}</td>
                <td class='align-middle'>$price zł</td>
                ";

      $sum += $row['price'];

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
                ";
      }
      echo "</tr>";
      $i++;
      if ($i == $count) {
        $sum = number_format($sum, 2);
        echo " <td class='align-middle' ><h4>Suma:</h4> </td> ";
        echo " <td colspan='2'class='align-middle' ><h4>$sum zł</h4> </td> ";
        $sum = 0;
        $i = 1;
      }
    }
    echo '</table>';
    // Zamówienia anulowane koniec


  } else {
    header("Location: $pHome");
  }
} else {
  header("Location: $pHome");
}
?>