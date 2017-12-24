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

// Redirect the user to index.php
if (Authentication::getInstance()->isNotLogged()) {
    Utils::getInstance()->goToLocation();
}

// Retrieves the user ID
$id = User::getInstance()->getIdByUsername($username)->fetch()['id'];

if (!isset($id)) {
    
    // Insert a user
    $user = array('username' => $username,
                    'password' => $password,
                    'active' => isset($active) ? 1 : 0,
                    'role' => $role);
    
    User::getInstance()->insertOne($user);
    Utils::getInstance()->goToLocation('../home.php');
} else if (empty($password) && empty($confirmPassword)) {   

    // Update the user without updating the password
    $user = array('id' => $id,
                    'active' => isset($active) ? 1 : 0,
                    'role' => $role);
    
    User::getInstance()->updateOne($user);
    Utils::getInstance()->goToLocation('../home.php');
} else if ($password === $confirmPassword) {
    
    // Update the user and his password
    $credentials = User::getInstance()->getCredentialsByUsername($username)->fetch();
    $digest = Authentication::getInstance()->getDigest("{$username}{$credentials['salt']}{$password}");
    
    $user = array('id' => $id,
                    'digest' => $digest,
                    'active' => isset($active) ? 1 : 0,
                    'role' => $role);
    
    User::getInstance()->updateOne($user);
    Utils::getInstance()->goToLocation('../home.php');
} else {
    Utils::getInstance()->goToLocation('../manageUser.php?id='.$id.'&error=true');
}

// Closes the connection to the database
Database::getInstance()->deconnection();
?>