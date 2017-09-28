<?php
extract(@$_POST);
require_once('Authentication.php');
require_once('User.php');

Authentication::getInstance()->toIndex();

$row = User::getInstance()->getUsername()->fetch();
$username = $row['username'];
$row = User::getInstance()->getCredentialsByUsername($username)->fetch();
$oldDigest = Authentication::getInstance()->hash($username.$row['salt'].$oldPassword);

if ($_SESSION['digest'] === $oldDigest && $newPassword === $confirmPassword) {
    $newDigest = Authentication::getInstance()->hash($username.$row['salt'].$newPassword);
    User::getInstance()->updateDigest($newDigest);
    $_SESSION['digest'] = $newDigest;
    header('location:logout.php');
} else {
    header('location:changePassword.php?error=true');
}
?>