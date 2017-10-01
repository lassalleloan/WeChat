<?php
/**************************************************
* STI - Project Web
* WeChat
* Description: web site to sen mails between users 
* Authors: Loan Lassalle, Wojciech Myszkorowski
**************************************************/

date_default_timezone_set('Europe/Zurich');
extract(@$_POST);
require_once('Authentication.php');
require_once('Mail.php');
require_once('User.php');

Authentication::getInstance()->goToLocation(Authentication::getInstance()->isNotLogged());

$idSender = User::getInstance()->getIdByUsername($from)->fetch()['id'];
$idReceiver = User::getInstance()->getIdByUsername($to)->fetch()['id'];

if (empty($idReceiver)) {
    header('location:writeMail.php?error=1');
} else {      
    $mail = array('date' => substr(date('Y-m-d\TH:i:s.u'), 0, 23),
                    'idSender' => $idSender,
                    'idReceiver' => $idReceiver,
                    'subject' => $subject,
                    'body' => $body);
    
    Mail::getInstance()->insertOne($mail);
    header('location:home.php');
}
?>