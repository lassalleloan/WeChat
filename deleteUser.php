<?php
/**
 * STI - Project Web
 * WeChat
 * Description: web site to send mails between users 
 * Authors: Loan Lassalle, Wojciech Myszkorowski
 */

extract(@$_GET);
require_once('models/Authentication.php');
require_once('models/Database.php');
require_once('models/User.php');

// Redirige l'utilisateur vers index.php
Authentication::getInstance()->goToLocation(Authentication::getInstance()->isNotLogged());

// Supprime l'utilisateur
User::getInstance()->deleteOne($id);

// Ferme la connexion à la base de données
Database::getInstance()->deconnection();

header('location:home.php');
?>