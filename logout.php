<?php
require_once("paths.php");

session_start();

session_unset();

header("Location: $pHome");
