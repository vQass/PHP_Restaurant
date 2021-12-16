<?php
session_start();
require_once('paths.php');
if (!isset($_SESSION['user_email'])) {
  header("Location: $pHome");
  exit();
}
require_once($pSharedFunctions);

$id = $_POST['delete'];
if(isset($_POST['delete'])){
  array_splice($_SESSION['koszyk'], $id, 2);
}
header("Location: $pOrders");

