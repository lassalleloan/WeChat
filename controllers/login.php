<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 */

extract(@$_POST);
require_once(dirname(__DIR__).'/models/Authentication.php');
session_start();

$MIN_LENGTH_USERNAME = 3;
$MAX_LENGTH_USERNAME = 50;
$MIN_LENGTH_PASSWORD = 8;
$MAX_LENGTH_PASSWORD = 50;

$strlenUsername = strlen($username);
$isCorrectUsername = is_string($username) && 
                        $strlenUsername >= $MIN_LENGTH_USERNAME && 
                        $strlenUsername <= $MAX_LENGTH_USERNAME;
                        
$strlenPassword = strlen($password);
$isCorrectPassword = is_string($password) && 
                        $strlenPassword >= $MIN_LENGTH_USERNAME && 
                        $strlenPassword <= $MAX_LENGTH_USERNAME;

// Authenticates the user
if ($isCorrectUsername && $isCorrectPassword) {
    // TODO: Filtres XSS, filtres SQL

    $_SESSION['logged'] = Authentication::getInstance()->isAuthenticated($username, $password);
}

// Redirect the user after authentication
if (isset($_SESSION['logged']) && $_SESSION['logged']) {
    $_SESSION['digest'] = Authentication::getInstance()->getDigest();
    header("location:../home.php");
} else {
    $_SESSION['logged'] = false;
    header("location:../index.php");
}
?> 
