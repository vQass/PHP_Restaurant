<?php

session_start();
require_once("paths.php");
require_once("$pSharedFunctions");
if (!isset($_SESSION['user_permission']) && $_SESSION['user_permission'] != "admin") {
  
  header("Location: $pHome");
  exit();
  
}
echo "<a href='menu.php' style='text-decoration: none; color: white;'>
<h3 style='border: 3px dotted white; width: 127px;text-align: center;'> ←Powrót do menu</h3></a>";




if (isset($_SESSION['ve_name'])) {
  $veName = $_SESSION['ve_name'];
  unset($_SESSION['ve_name']);
} else $veName = "";

if (isset($_SESSION['ve_price'])) {
  $vePrice = $_SESSION['ve_price'];
  unset($_SESSION['ve_price']);
} else $vePrice = "";

if (isset($_SESSION['ve_photo'])) {
  $vePhoto = $_SESSION['ve_photo'];
  unset($_SESSION['ve_photo']);
} else $vePhoto = "";

if (isset($_SESSION['ve_description'])) {
  $veDescription = $_SESSION['ve_description'];
  unset($_SESSION['ve_description']);
} else $veDescription = "";
?>

<!DOCTYPE HTML>
<html lang="pl">

<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <style>
    body {
      background-color: black;
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

  <div class='absolute'>
    <?php
    if (isset($_SESSION['general_message'])) {
      echo $_SESSION['general_message'];
      unset($_SESSION['general_message']);
    }
    ?>
  </div>
  <div class="mb-3" style="margin-top: 40px;">
    <h1>Dodawanie produktu do menu:</h1>

    <form action='<?php echo $pMenuAddValidation ?>' method='POST' class='g-3' enctype='multipart/form-data'>
      <div class="col-md-12">
        <label for="inputName" class="form-label">Nazwa</label>
        <input type="text" class="form-control <?php echo $veName ?>" id="Name" name="name" placeholder="Pizza" ?>
      </div>

      <div class="col-md-12">
        <label for="inputCategory" class="form-label">Kategoria</label>
        <select class="form-select" id="inputCategory" name="category">
          
          <option value="Pizza" >Pizza</option>
          <option value="Piwo" >Piwo</option>
        </select>
      </div>
      <div class="col-md-12">
        <label for="inputPrice" class="form-label">Cena</label>
        <input type="text" class="form-control <?php echo $vePrice ?>" id="Price" name="price" placeholder="00.00" ?>
      </div>

      <div class="col-md-12">
        <label for="inputPhoto" class="form-label">Zdjęcie</label>
        <input type="file" name="fileToUpload" id="fileToUpload" class="form-select <?php echo $vePhoto ?>">
      </div>

      <div class="col-md-12">
        <label for="inputDescription" class="form-label">Opis</label>
        <textarea id="Description" name="description" rows="4" cols="50" class="form-select <?php echo $veDescription ?>"></textarea>
      </div>
      <br />
      <div class="col-12">
        <button class="btn btn-primary" type="submit">Zatwierdź</button>
      </div>
    </form>
  </div>
</body>

</html>