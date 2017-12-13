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

// Redirect the user to index.php
Authentication::getInstance()->goToLocation(Authentication::getInstance()->isNotLogged());

// Retrieves user name, salt and user's fingerprint
$username = User::getInstance()->getUsername()->fetch()['username'];
$salt = User::getInstance()->getCredentialsByUsername($username)->fetch()['salt'];
$oldDigest = Authentication::getInstance()->getDigest("{$username}{$salt}{$oldPassword}");

// Authorizes and authenticates the user
if ($_SESSION['digest'] === $oldDigest && $newPassword === $confirmPassword) {
    $newDigest = Authentication::getInstance()->getDigest("{$username}{$salt}{$newPassword}");
    User::getInstance()->updateDigest($newDigest);
    
    $_SESSION['digest'] = $newDigest;
    header('location:logout.php');
} else {
    header('location:../changePassword.php?error=1');
}

// Closes the connection to the database
Database::getInstance()->deconnection();
?>