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
require_once('Utils.php');

Authentication::getInstance()->goToLocation(Authentication::getInstance()->isNotLogged());

$id = User::getInstance()->getIdByUsername($username)->fetch()['id'];

if (!isset($id)) {
    $user = array('username' => $username,
                    'password' => $password,
                    'active' => isset($active) ? 1 : 0,
                    'role' => $role);
    
    User::getInstance()->insertOne($user);
    header('location:home.php');
} else if (empty($password) && empty($confirmPassword)) {    
    $user = array('id' => $id,
                    'active' => isset($active) ? 1 : 0,
                    'role' => $role);
    
    User::getInstance()->updateOne($user);
    header('location:home.php');
} else if ($password === $confirmPassword) {
    $credentials = User::getInstance()->getCredentialsByUsername($username)->fetch();
    $digest = Authentication::getInstance()->getDigest("{$username}{$credentials['salt']}{$password}");
    
    $user = array('id' => $id,
                    'digest' => $digest,
                    'active' => isset($active) ? 1 : 0,
                    'role' => $role);
    
    User::getInstance()->updateOne($user);
    header('location:home.php');
} else {
    header('location:manageUser.php?id='.$id.'&error=true');
}

Database::getInstance()->deconnection();
?>