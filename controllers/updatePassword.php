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
Authentication::getInstance()->redirectIfIsNotLogged();

$MIN_LENGTH_PASSWORD = 8;
$MAX_LENGTH_PASSWORD = 50;

$strlenOldPassword = strlen($oldPassword);
$isCorrectOldPassword = is_string($oldPassword) && 
                        $strlenOldPassword >= $MIN_LENGTH_PASSWORD && 
                        $strlenOldPassword <= $MAX_LENGTH_PASSWORD;

$strlenNewPassword = strlen($newPassword);
$isCorrectNewPassword = is_string($newPassword) && 
                        $strlenNewPassword >= $MIN_LENGTH_PASSWORD && 
                        $strlenNewPassword <= $MAX_LENGTH_PASSWORD;

$strlenConfirmPassword = strlen($confirmPassword);
$isCorrectConfirmPassword = is_string($confirmPassword) && 
                            $strlenConfirmPassword >= $MIN_LENGTH_PASSWORD && 
                            $strlenConfirmPassword <= $MAX_LENGTH_PASSWORD;

// Check inputs
if ($isCorrectOldPassword && $isCorrectNewPassword && $isCorrectConfirmPassword) {
    // TODO: Filtres XSS, filtres SQL

    // Retrieves user name, salt and user's fingerprint
    $username = User::getInstance()->getUsername()->fetch()['username'];
    $salt = User::getInstance()->getCredentialsByUsername($username)->fetch()['salt'];
    $oldDigest = Authentication::getInstance()->hashStr("{$username}{$salt}{$oldPassword}");
}

// Authenticates the user
if (isset($oldDigest) && $_SESSION['digest'] === $oldDigest && $newPassword === $confirmPassword) {
    $salt = Utils::getInstance()->randomStr();
    User::getInstance()->updateSalt($salt);

    $newDigest = Authentication::getInstance()->hashStr("{$username}{$salt}{$newPassword}");
    User::getInstance()->updateDigest($newDigest);
    
    header("location:logout.php");
} else {
    header("location:../changePassword.php?isError=true");
}

// Closes the connection to the database
Database::getInstance()->deconnection();
?>
