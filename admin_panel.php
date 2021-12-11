<?php
session_start();
require_once("paths.php"); //test
if (!isset($_SESSION['user_permission']) || $_SESSION['user_permission'] != "admin") {
  header("Location: $pHome");
  exit();
}
?>
<!DOCTYPE HTML>
<html lang="pl">

<head>
  <title>Panel administatora</title>
  <link href="/images/dot_ico/admin.ico" rel="icon" type="image/x-icon" />
  <link rel="stylesheet" href="styles.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <script src="jquery.js"></script>
  <script src="index.js"></script>

</head>

<body id="backgroundImg">

  <nav class="navbar navbar-expand-xxl navbar-dark">
    <div class="adminNav">
      <a class="navbar-brand"><img id="logoImg" src="images/logo.jpg" alt="Logo"></a>
      <a class="navbar-brand logomen">Restau<span class="fast-flicker">racja</span> u <span class="flicker">Mentzena</span></a>
    </div>
  </nav>

  <div class="adminMain">

    <a href="index.php">
      <div class="adminPanel">
        Strona główna
      </div>
    </a>
    <a href="menu.php">
      <div class="adminPanel">
        Menu
      </div>
    </a>
    <a href="users_list.php">
      <div class="adminPanel">
        Lista użytkowników
      </div>
    </a>
  </div>

  </div>
</body>

</html>