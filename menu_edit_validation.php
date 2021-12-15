<?php
session_start();
require_once "paths.php";
require_once "connect.php";
if (!isset($_SESSION['user_permission']) && $_SESSION['user_permission'] != "admin") {
  
    header("Location: $pHome");
    exit();
    
  }
$id = $_SESSION['eu_id'];
$name = $_POST['name'];
$name1 = $_POST['category'];
$name2 = $_POST['price'];
$name3 = $_POST['photo'];
$name4 = $_POST['description'];
$data_valid = true;
if ($name != "") {
    if ((strlen($name) < 2) || (strlen($name) > 20)) {
      $data_valid = false;
      $_SESSION['ve_name'] = 'is-invalid';
    } else {
      $_SESSION['ve_name'] = 'is-valid';
    }
  }

   if ($name2 != "") {
     if ( is_numeric($name2)==true) {
         $_SESSION['ve_price'] = 'is-valid';
     } else {
       $data_valid = false;
       $_SESSION['ve_price'] = 'is-invalid';
     }
   }

$target_folder = "Menu/";
$target_file = $target_folder . basename($_FILES["fileToUpload"]["name"]);
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check != false) {
      echo "File is an image - " . $check["mime"] . ".";
      $data_valid = true;
      
      // Ograniczenie w przesyłaniu duzych plików
      if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $data_valid = false;
      }
      
      // Możliwość wysyłania plików tylko z podanymi rozszerzeniami 
      if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
      && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $data_valid = false;
      }
    } else {
      echo "File is not an image.";
      $data_valid = false;
    }
  }

  
  // Sprawdza
  if ($data_valid == false) {
    $name3 = $_POST['photo'];
    $_SESSION['ve_photo'] ='is-invalid';
  } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $name3 = basename($_FILES["fileToUpload"]["name"]);
        $_SESSION['ve_price'] = 'is-valid';
    } else {
        $_SESSION['ve_photo'] ='is-invalid';
    }
  }

  if ($data_valid) {
    try {
      // Łączenie z bazą danych jeśli reszta danych jest poprawna
      require_once($pDbConnection);
    } catch (Exception $e) {

      $_SESSION['general_message'] .= ErrorMessageGenerator("Błąd serwera!");

      $data_valid = false;
    }
}
    if ($data_valid) {
        try {
                $sql ="UPDATE menu SET name = ?, category = ?, price = ?, photo = ?,                      
                description = ? WHERE id = ?";
                $stmt = $dbh->prepare($sql);
                $result = $stmt->execute([$name,$name1,$name2,$name3,$name4,$_SESSION['eu_id']]);
                unset($_SESSION['eu_id']);
                unset($_SESSION['ve_price']);
                unset($_SESSION['ve_name']);
                unset($_SESSION['ve_photo']);
        }catch (Exception $e) {

            $_SESSION['general_message'] .= ErrorMessageGenerator("Błąd podczas edycji użytkownika");
            $_SESSION['general_message'] .= ErrorMessageGenerator("$e");
      
            header("Location: $pMenuEdit");
            exit();
        }
        header("Location: $pMenu");
        exit(); 
    }else{
            header("Location: $pMenuEdit");
            exit();
    }