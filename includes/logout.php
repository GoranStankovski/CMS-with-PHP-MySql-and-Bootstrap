<?php ob_start();?>
<?php session_start(); ?>
<?php
$_SESSION['username'] = $db_username = null; 
$_SESSION['firstname'] = $db_user_firstname = null;
$_SESSION['lastname'] = $db_user_lastname = null;
$_SESSION['user_role'] = $db_user_role = null;
header("Location: ../index.php");
?>