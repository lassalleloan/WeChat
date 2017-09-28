<?php
extract(@$_GET); 
require_once('Authentication.php');
require_once('Mail.php');

Authentication::getInstance()->toIndex();
Mail::getInstance()->delete($id);
header('location:home.php');
?>