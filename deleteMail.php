<?php     
require_once('Authentication.php');
require_once('Mail.php');
extract(@$_GET);

Authentication::getInstance()->toIndex();
Mail::getInstance()->delete($id);
header('location:home.php');
?>