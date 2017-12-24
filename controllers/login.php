<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 */

extract(@$_POST);
require_once(dirname(__DIR__).'/models/Authentication.php');
require_once(dirname(__DIR__).'/models/Database.php');
require_once(dirname(__DIR__).'/models/User.php');
require_once(dirname(__DIR__).'/models/Utils.php');
session_start();

// Redirect the user to home.php
if (isset($_SESSION['logged']) && $_SESSION['logged']) {
    Utils::getInstance()->goToLocation('../home.php');
    exit();
}

// Retrieves the credentials of the user
$credentials = User::getInstance()->getCredentialsByUsername($username)->fetch();

// Computes the user's fingerprint
$digest = Authentication::getInstance()->getDigest("{$username}{$credentials['salt']}{$password}");

// Retrieves the account status of the user
$active = User::getInstance()->getActiveByUsername($username)->fetch()['active'];

// Closes the connection to the database
Database::getInstance()->deconnection();

// Authorizes and authenticates the user
$_SESSION['logged'] = $credentials['digest'] === $digest && $active;

// Redirect the user
if ($_SESSION['logged']) {
    $_SESSION['digest'] = $digest;
    
    Utils::getInstance()->goToLocation('../home.php');
} else {
    Utils::getInstance()->goToLocation('../index.php');
}
?> 
