<?php
session_start();
require_once "paths.php";
if (!isset($_SESSION['user_permission']) && $_SESSION['user_permission'] != "admin") {
  
  header("Location: $pHome");
  exit();
  
}

$data_valid = true;
if ($data_valid) {
  try {
    // Łączenie z bazą danych jeśli reszta danych jest poprawna
    require_once($pDbConnection);
  } catch (Exception $e) {

    $_SESSION['general_message'] .= ErrorMessageGenerator("Błąd serwera!");
  }
}
$name = $_POST['name'];
$name1 = $_POST['category'];
$name2 = $_POST['price'];
$name4 = $_POST['description'];
$tempQuery = $dbh->query('SELECT id FROM menu');
$temp=$tempQuery->rowCount();
$id=$temp+1;
if ($name != "") {
  if ((strlen($name) < 2) || (strlen($name) > 20)) {
    $data_valid = false;
    $_SESSION['ve_name'] = 'is-invalid';
  } else {
    $_SESSION['ve_name'] = 'is-valid';
  }
}else{
  $_SESSION['ve_name'] = 'is-invalid';
}

 if ($name2 != "") {
   if ( is_numeric($name2)==true) {
       $_SESSION['ve_price'] = 'is-valid';
   } else {
     $data_valid = false;
     $_SESSION['ve_price'] = 'is-invalid';
   }
 }else{
  $_SESSION['ve_price'] = 'is-invalid';
 }

 if ($name4 == "") {
  $_SESSION['ve_description'] = 'is-invalid';
  } 
$target_folder = "Menu/";
$name3 = basename($_FILES["fileToUpload"]["name"]);
$target_file = $target_folder . basename($_FILES["fileToUpload"]["name"]);
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check != false) {
      echo "File is an image - " . $check["mime"] . ".";
      $data_valid = true;
    } else {
      echo "File is not an image.";
      $data_valid = false;
    }
  }
  
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
  
  // Sprawdza
  if ($data_valid == false) {
    $_SESSION['ve_photo'] ='is-invalid';
  } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      $_SESSION['ve_price'] = 'is-valid';
    } else {
      $_SESSION['ve_photo'] ='is-invalid';
    }
  }
  if($data_valid){
    try {
      $sql ="INSERT  INTO menu (id,name, category, price, photo,                      
      description) VALUES(?,?,?,?,?,?)";
      $stmt = $dbh->prepare($sql);
  
      $result = $stmt->execute([$id,$name,$name1,$name2,$name3,$name4]);
      unset($_SESSION['ve_price']);
      unset($_SESSION['ve_name']);
      unset($_SESSION['ve_photo']);
      unset($_SESSION['ve_description']);
    } catch (Exception $e) {
      $_SESSION['general_message'] .= ErrorMessageGenerator("Błąd podczas dodawania produktu!");
      $_SESSION['general_message'] .= ErrorMessageGenerator($e);
      header("Location: $pMenuAdd");
      exit();
    }
    header("Location: $pMenu");
    exit();
  }else{
    header("Location: $pMenuAdd");
    exit();
  }