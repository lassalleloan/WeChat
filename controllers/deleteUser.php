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
require_once(dirname(__DIR__).'/models/Utils.php');

// Redirect the user to index.php
if (Authentication::getInstance()->isNotLogged()) {
    Utils::getInstance()->goToLocation();
}

// Deletes the user
User::getInstance()->deleteOne($id);

// Closes the connection to the database
Database::getInstance()->deconnection();

Utils::getInstance()->goToLocation('../home.php');
?>