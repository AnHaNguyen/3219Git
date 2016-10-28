<?php
ob_start();
session_start();
$current_page = 'Logout';
include_once('./template/header.php');
include_once('./template/navbar.php');
unset($_SESSION['loginuser']);
header("Location:index.php");
ob_end_flush();
?>

