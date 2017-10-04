<?php
/**************************************************
* STI - Project Web
* WeChat
* Description: web site to sen mails between users 
* Authors: Loan Lassalle, Wojciech Myszkorowski
**************************************************/

extract(@$_GET);
require_once('Authentication.php');
require_once('User.php');

Authentication::getInstance()->goToLocation(Authentication::getInstance()->isNotLogged());
User::getInstance()->deleteOne($id);
header('location:home.php');
?>