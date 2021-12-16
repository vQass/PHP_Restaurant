<?php
session_start();
require_once "paths.php";
//unset($_SESSION['koszyk']);
if (!isset($_SESSION['user_email'])) {
  header("Location: $pHome");
  exit();
}
if (!isset($_SESSION['koszyk']))
    {
      $_SESSION['koszyk']=array();
    }
    $data_valid = true;
if(isset($_POST['ile'])&& isset($_POST['product'])){
    $number = $_POST['ile'];
    $product = $_POST['product'];
}

for($i=0;$i<count($_SESSION['koszyk']);$i=$i+2){
    if($product==$_SESSION['koszyk'][$i]){
      $_SESSION['koszyk'][$i+1]+=$number;
      $data_valid=false;
    }
}

if ($number != "") {
    if ( !preg_match("/^[0-9]$/", $number)) {
      $_SESSION['ve_number'] = 'is-invalid';
      $data_valid = false;
    } else {
      $_SESSION['ve_number'] = 'is-valid';
    }
  }else {
    $_SESSION['ve_number']= 'is-invalid';
    $data_valid = false;
}

if($data_valid){
    array_push($_SESSION['koszyk'],$product,$number);
}
header("Location: $pOrders");