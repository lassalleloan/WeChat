<?php
require_once('Authentication.php');
require_once('User.php');
extract(@$_GET);

Authentication::getInstance()->toIndex();
User::getInstance()->delete($id);
header('location:home.php');
?>