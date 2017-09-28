<?php
extract(@$_POST);
require_once('Authentication.php');
require_once('User.php');
session_start();

$row_1 = User::getInstance()->getCredentialsByUsername($username)->fetch();
$row_2 = User::getInstance()->getActiveByUsername($username)->fetch();
$digestComp = Authentication::getInstance()->hash($username.$row_1['salt'].$password);
$_SESSION['logged'] = $row_1['digest'] === $digestComp && $row_2['active'];

if ($_SESSION['logged']) {
    $_SESSION['digest'] = $digestComp;
	header('location:home.php');
} else {	
	header('location:index.php');
}
?> 
