<?php
@session_start();
require_once("paths.php");
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

    // Menu
    try {
      $menuQuery = $dbh->query("SELECT * FROM menu ");
    } catch (Exception $e) {
      $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania do bazy danych");
      $_SESSION['general_message'] .= ErrorMessageGenerator($e);
    }
    echo "<a href='$pAdminPanel' style='text-decoration: none; color: white;'>
    <h3 style='width: 127px;text-align: center;'> ←Powrót</h3></a>";

    echo "<form method='post' action ='$pMenuAdd' style='text-align: center;'> 
    
    <button name='Add' class='btn btn-outline-warning btn-lg' style='margin-top: 15vh; margin-bottom: 2vh;' type='submit'>Dodaj do Menu</button>
    </form>";
    echo "<h1 style='text-align: center;'>Menu</h1>";
    echo '<table class="center table " style="width: 80%; margin-left:auto; margin-right:auto">
            <tr>
                <th>ID</th>
                <th>Nazwa</th>
                <th>Kategoria</th>
                <th>Cena</th>
                <th>Zdjęcie</th>
                <th>Opis</th>
                <th>Akcja</th>
            </tr>';
    $menu = $menuQuery->fetchAll();
    foreach ($menu as $temp) {
      echo "<tr id='tlo'>
    <td>{$temp['id']}</td>
    <td>{$temp['name']}</td>
    <td>{$temp['category']}</td>
    <td>";
      echo number_format($temp['price'], 2);
      echo "</td>
    <td>{$temp['photo']}</td>";
      echo '<td>' . nl2br("{$temp['description']}") . '</td>';
      echo "
    <td >
        <form method='post' action ='$pMenuEdit'> 
            <button name='edit' class='btn btn-outline-primary' style='width: 70px' type='submit' value='{$temp['id']}'>Edytuj</button>
        </form>
        <form method='post' action='$pMenuDelete'> 
            <button name='delete' class='btn btn-outline-danger' style='width: 70px; margin-top:5px;' type='submit' value='{$temp['id']}'>Usuń</button>
        </form>  
        </td>
    </tr>";
    }
    echo '</table>';
  } else {
    header("Location: $pHome");
  }
} else {
  header("Location: $pHome");
}
?>
<html>

<head>
  <link href="/images/dot_ico/menu.ico" rel="icon" type="image/x-icon" />
  <link rel="stylesheet" href="styles.css">
  <title>Menu</title>
  <style>
    body {
      background-color: rgba(0, 0, 0, 0.90) !important;
      color: white !important;
    }

    #tlo:hover {
      background-color: RGBA(243, 111, 39, 0.5);
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

    .table>tbody>tr>td {
      vertical-align: middle;
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</head>

<body>
  <nav class="navbar navbar-expand-xxl navbar-dark">
    <div class="adminNav">
      <a class="navbar-brand" href="index.php"><img id="logoImg" src="images/logo.jpg" alt="Logo"></a>
      <a class="navbar-brand logomen" href="index.php">Restau<span class="fast-flicker">racja</span> u <span class="flicker">Mentzena</span></a>
    </div>
  </nav>
</body>

</html>