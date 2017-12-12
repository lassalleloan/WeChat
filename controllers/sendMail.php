<?php
/**
 * STI - Project Web
 * WeChat
 * Description: web site to send mails between users 
 * Authors: Loan Lassalle, Wojciech Myszkorowski
 */

date_default_timezone_set('Europe/Zurich');
extract(@$_GET);
extract(@$_POST);
require_once(dirname(__DIR__).'/models/Authentication.php');
require_once(dirname(__DIR__).'/models/Database.php');
require_once(dirname(__DIR__).'/models/Mail.php');
require_once(dirname(__DIR__).'/models/User.php');

// Redirige l'utilisateur vers index.php
Authentication::getInstance()->goToLocation(Authentication::getInstance()->isNotLogged());

// Récupère l'expéditeur et le destinataire de l'email
$idSender = User::getInstance()->getIdByUsername($from)->fetch()['id'];
$idReceiver = User::getInstance()->getIdByUsername($to)->fetch()['id'];

// Redirige l'utilisateur
if (isset($id) && empty($idReceiver)) {
    header('location:'.dirname(__DIR__).'/writeMail.php?id={$id}&error=1');
} else if (empty($idReceiver)) {
    header('location:'.dirname(__DIR__).'/writeMail.php?error=1');
} else {
    
    // Insère un email
    $mail = array('date' => substr(date('Y-m-d\TH:i:s.u'), 0, 23),
                    'idSender' => $idSender,
                    'idReceiver' => $idReceiver,
                    'subject' => $subject,
                    'body' => $body);
    
    Mail::getInstance()->insertOne($mail);
    
    header('location:'.dirname(__DIR__).'/home.php');
}

// Ferme la connexion à la base de données
Database::getInstance()->deconnection();
?>