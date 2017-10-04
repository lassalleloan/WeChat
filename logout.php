<?php
/**************************************************
* STI - Project Web
* WeChat
* Description: web site to sen mails between users 
* Authors: Loan Lassalle, Wojciech Myszkorowski
**************************************************/

require_once('Database.php');

session_start();
session_destroy();

Database::getInstance()->deconnection();
header('location:index.php');
?> 
