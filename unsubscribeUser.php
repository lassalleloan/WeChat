<?php
extract(@$_GET);
require_once('Authentication.php');
require_once('User.php');

Authentication::getInstance()->toIndex();
User::getInstance()->delete($id);
header('location:home.php');
?>