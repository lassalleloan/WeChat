<?php
/**************************************************
* STI - Project Web
* WeChat
* Description: web site to sen mails between users 
* Authors: Loan Lassalle, Wojciech Myszkorowski
**************************************************/

extract(@$_GET);
require_once('Authentication.php');
require_once('Database.php');
require_once('Mail.php');

Authentication::getInstance()->goToLocation(Authentication::getInstance()->isNotLogged());

Mail::getInstance()->deleteOne($id);
Database::getInstance()->deconnection();

header('location:home.php');
?>