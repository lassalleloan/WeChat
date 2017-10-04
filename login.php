<?php
/**************************************************
* STI - Project Web
* WeChat
* Description: web site to sen mails between users 
* Authors: Loan Lassalle, Wojciech Myszkorowski
**************************************************/

extract(@$_POST);
require_once('Authentication.php');
require_once('User.php');
session_start();

if (isset($_SESSION['logged']) && $_SESSION['logged']) {
    header("location:home.php");
    exit();
}

$credentials = User::getInstance()->getCredentialsByUsername($username)->fetch();
$digest = Authentication::getInstance()->getDigest("{$username}{$credentials['salt']}{$password}");

$active = User::getInstance()->getActiveByUsername($username)->fetch()['active'];

$_SESSION['logged'] = $credentials['digest'] === $digest && $active;

if ($_SESSION['logged']) {
    $_SESSION['digest'] = $digest;
	header('location:home.php');
} else {	
	header('location:index.php');
}
?> 
