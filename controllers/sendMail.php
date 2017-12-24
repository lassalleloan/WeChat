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
require_once(dirname(__DIR__).'/models/Utils.php');

// Redirect the user to index.php
if (Authentication::getInstance()->isNotLogged()) {
    Utils::getInstance()->goToLocation();
}

// Retrieves the sender and the recipient of the email
$idSender = User::getInstance()->getIdByUsername($from)->fetch()['id'];
$idReceiver = User::getInstance()->getIdByUsername($to)->fetch()['id'];

// Redirect the user
if (isset($id) && empty($idReceiver)) {
    Utils::getInstance()->goToLocation('../writeMail.php?id={$id}&error=1');
} else if (empty($idReceiver)) {
    Utils::getInstance()->goToLocation('../writeMail.php?error=1');
} else {
    
    // Insert an email
    $mail = array('date' => substr(date('Y-m-d\TH:i:s.u'), 0, 23),
                    'idSender' => $idSender,
                    'idReceiver' => $idReceiver,
                    'subject' => $subject,
                    'body' => $body);
    
    Mail::getInstance()->insertOne($mail);
    Utils::getInstance()->goToLocation('../home.php');
}

// Closes the connection to the database
Database::getInstance()->deconnection();
?>