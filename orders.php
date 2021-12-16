<?php
session_start();
if (!isset($_SESSION['user_email'])) {
  header("Location: $pHome");
  exit();
}
if (!isset($_SESSION['koszyk'])) {
  $_SESSION['koszyk'] = array();
}
require_once("paths.php");
require_once("$pSharedFunctions");
echo "<a href='menu.php' style='text-decoration: none; color: white;'>
<h3 style='border: 3px white; white-space: nowrap; width: 127px;text-align: center;'> ←Powrót</h3></a>";
try {
  require_once "$pDbConnection";
} catch (Exception $e) {
  $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas łączenia z bazą danych");
  $_SESSION['general_message'] .= ErrorMessageGenerator($e);
  $dbConnected = false;
}
try {
  $menuQuery = $dbh->query("SELECT id,name FROM menu ");
} catch (Exception $e) {
  $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania do bazy danych");
  $_SESSION['general_message'] .= ErrorMessageGenerator($e);
}
$menu = $menuQuery->fetchAll();

if (isset($_SESSION['ve_number'])) {
  $veNumber = $_SESSION['ve_number'];
  unset($_SESSION['ve_number']);
} else $veNumber = "";
?>

<!DOCTYPE HTML>
<html lang="pl">

<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <style>
    body {
      background-color: rgba(0, 0, 0, 0.90);
      color: white;
    }

    .mb-3 {
      width: 30%;
      margin-left: auto;
      margin-right: auto;
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
  </style>
</head>

<body>
  <div class="absolute">
    <?php
    if (isset($_SESSION['general_message'])) {
      echo $_SESSION['general_message'];
      unset($_SESSION['general_message']);
    }
    ?>
  </div>

  <div class="mb-3" style="margin-top: 40px;">
    <h1>Składanie zamówienia:</h1>

    <form action="<?php echo $pOrderAdd ?>" method='POST' class='g-3'>

      <div class="col-md-12">
        <label for="inputProduct" class="form-label">Lista produktow</label>
        <select class="form-select" id="inputProduct" name="product">
          <?php foreach ($menu as $temp) {
            echo "<option value='{$temp['id']}'>{$temp['name']}</option>";
          } ?>
        </select>
      </div>

      <div class="col-md-12">
        <label for="inputIlosc" class="form-label">Ilosc</label>
        <input type="text" name="ile" id="inputIlosc" class="form-control <?php echo $veNumber ?>" value='1' />
      </div>
      <br>
      <div class="col-12">
        <button class="btn btn-primary" type="submit" name="sumbit">Dodaj do koszyka</button>
      </div>
    </form>
  </div>
  <?php
  if (isset($_SESSION['koszyk'])) {
    echo '<table class="center table " style="width: 80%; margin-left:auto; margin-right:auto; color: red;">
            <tr>
                <th>Nazwa</th>
                <th>Ilosc</th>
                <th>Akcja</th>
            </tr>';
    for ($i = 0; $i < count($_SESSION['koszyk']); $i = $i + 2) {
      $temp = $_SESSION['koszyk'][$i];
      $nameQuery = $dbh->query("SELECT name FROM menu WHERE id=$temp");
      $name = $nameQuery->fetch();
      echo '<tr >';
      echo "<td>{$name['name']}</td>";
      echo "<td>{$_SESSION['koszyk'][$i + 1]}</td>";
      echo "<td>
            <form method='post' action='$pOrderDelete'> 
            <button name='delete' class='btn btn-outline-danger' type='submit' value='{$i}'>Usuń</button>
            </form>
            </td>";
      echo "</tr>";
    }
    echo "</table>";
  }
  ?>
  <div class="mb-3" style="margin-top: 40px;">
    <form method="POST" action="<?php echo $pOrdersDetails ?>">
      <div class="col-12">
        <button class="btn btn-primary" type="submit" name="dodaj_zam">Złóż zamówienie</button>
      </div>
    </form>
  </div>
</body>

</html>