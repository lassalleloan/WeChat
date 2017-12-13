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
require_once(dirname(__DIR__).'/models/Mail.php');

// Redirect the user to index.php
Authentication::getInstance()->goToLocation(Authentication::getInstance()->isNotLogged());

// Delete the email
Mail::getInstance()->deleteOne($id);

// Closes the connection to the database
Database::getInstance()->deconnection();

header('location:../home.php');
?>