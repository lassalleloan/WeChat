<?php
extract(@$_POST);
require_once('Authentication.php');
require_once('User.php');

Authentication::getInstance()->toIndex();

$row = User::getInstance()->getIdByUsername($username)->fetch();
$id = $row['id'];

if (empty($newPassword) && empty($confirmPassword)) {
    User::getInstance()->updateUser($id, $active === 'True'? 1 : 0, $role);
    
    header('location:home.php');
} else if (!empty($newPassword) && !empty($confirmPassword) && $newPassword === $confirmPassword) {    
    $row = User::getInstance()->getCredentialsByUsername($username)->fetch();
    $newDigest = Authentication::getInstance()->hash($username.$row['salt'].$newPassword);
    User::getInstance()->updateUserAll($id, $newDigest, $active === 'True'? 1 : 0, $role);
    
    header('location:home.php');
} else {
    header('location:manageUser.php?id='.$id.'&error=true');
}
?>