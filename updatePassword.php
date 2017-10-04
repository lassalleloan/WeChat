<?php
/**************************************************
* STI - Project Web
* WeChat
* Description: web site to sen mails between users 
* Authors: Loan Lassalle, Wojciech Myszkorowski
**************************************************/

extract(@$_POST);
require_once('Authentication.php');
require_once('Database.php');
require_once('User.php');

Authentication::getInstance()->goToLocation(Authentication::getInstance()->isNotLogged());

$username = User::getInstance()->getUsername()->fetch()['username'];
$salt = User::getInstance()->getCredentialsByUsername($username)->fetch()['salt'];
$oldDigest = Authentication::getInstance()->getDigest("{$username}{$salt}{$oldPassword}");

if ($_SESSION['digest'] === $oldDigest && $newPassword === $confirmPassword) {
    $newDigest = Authentication::getInstance()->getDigest("{$username}{$salt}{$newPassword}");
    User::getInstance()->updateDigest($newDigest);
    
    $_SESSION['digest'] = $newDigest;
    header('location:logout.php');
} else {
    header('location:changePassword.php?error=1');
}

Database::getInstance()->deconnection();
?>