<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 */

extract(@$_GET);
require_once(dirname(__DIR__).'/models/Authentication.php');
require_once(dirname(__DIR__).'/models/Database.php');
require_once(dirname(__DIR__).'/models/User.php');

// Redirect the user to index.php
Authentication::get_instance()->redirect_if_is_not_logged();

$id = isset($id) ? (int)$id : 0;

// Deletes the user
if ($id >= Database::PHP_INT_MIN && $id <= Database::PHP_INT_MAX) {
    User::get_instance()->redirect_if_is_associate_to_user($id);
    User::get_instance()->delete_one($id);

    // Closes the connection to the database
    Database::get_instance()->deconnection();
}

header('location:../home.php');
?>
