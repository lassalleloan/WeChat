<?php     
require_once('Authentication.php');
require_once('Mail.php');
require_once('User.php');
extract(@$_POST);

date_default_timezone_set('Europe/Zurich');

Authentication::getInstance()->check();

$row = User::getInstance()->getId($from)->fetch();
$idSender = $row['id'];

$row = User::getInstance()->getId($to)->fetch();
$idReceiver = $row['id'];
            
$mail = array(
            array('date' => substr(date('Y-m-d\TH:i:s.u'), 0, 23),
                'idSender' => $idSender,
                'idReceiver' => $idReceiver,
                'subject' => $subject,
                'body' => $body)
            );
            
Mail::getInstance()->sendMail($mail);

header('location:home.php');
?>