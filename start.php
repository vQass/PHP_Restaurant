<?php
require_once("paths.php");



if(!isset($_SESSION['login']))
{
  if(isset($_SESSION['error']))
  {
    echo $_SESSION['error'];
  }
  else
  {
    header("Location: $pSignInView");
  }
}
else
{
  echo "Witaj {$_SESSION['login']}!";
}
