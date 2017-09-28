<?php     
require_once('Authentication.php');
require_once('Mail.php');
require_once('User.php');
extract(@$_POST);

date_default_timezone_set('Europe/Zurich');

Authentication::getInstance()->toIndex();

$row = User::getInstance()->getIdByUsername($from)->fetch();
$idSender = $row['id'];

$row = User::getInstance()->getIdByUsername($to)->fetch();
$idReceiver = $row['id'];

if (empty($idReceiver)) {
    header('location:writeMail.php?error=true');
} else {      
    $mail = array(
                array('date' => substr(date('Y-m-d\TH:i:s.u'), 0, 23),
                    'idSender' => $idSender,
                    'idReceiver' => $idReceiver,
                    'subject' => $subject,
                    'body' => $body)
                );
                
    Mail::getInstance()->insert($mail);
    header('location:home.php');
}
?>