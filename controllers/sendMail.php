<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 */

date_default_timezone_set('Europe/Zurich');
extract(@$_GET);
extract(@$_POST);
require_once(dirname(__DIR__).'/models/Authentication.php');
require_once(dirname(__DIR__).'/models/Database.php');
require_once(dirname(__DIR__).'/models/Mail.php');
require_once(dirname(__DIR__).'/models/User.php');

// Redirect the user to index.php
Authentication::getInstance()->goToLocation(Authentication::getInstance()->isNotLogged());

// Retrieves the sender and the recipient of the email
$idSender = User::getInstance()->getIdByUsername($from)->fetch()['id'];
$idReceiver = User::getInstance()->getIdByUsername($to)->fetch()['id'];

// Redirect the user
if (isset($id) && empty($idReceiver)) {
    header('location:../writeMail.php?id={$id}&error=1');
} else if (empty($idReceiver)) {
    header('location:../writeMail.php?error=1');
} else {
    
    // Insert an email
    $mail = array('date' => substr(date('Y-m-d\TH:i:s.u'), 0, 23),
                    'idSender' => $idSender,
                    'idReceiver' => $idReceiver,
                    'subject' => $subject,
                    'body' => $body);
    
    Mail::getInstance()->insertOne($mail);
    
    header('location:../home.php');
}

// Closes the connection to the database
Database::getInstance()->deconnection();
?>