<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 */

require_once(dirname(__DIR__).'/models/Database.php');

session_start();
session_destroy();

// Closes the connection to the database
Database::getInstance()->deconnection();

// Redirect the user to index.php
header("location:../index.php");
?> 
