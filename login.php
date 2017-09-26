<?php
require ('classes/Database.php');

session_start();
extract(@$_POST);

$database = new Database;
$results = $database->query('SELECT * FROM users WHERE username="'.$username.'";');
$results = $results->fetch();

if (sizeof($results) > 0) {
	$_SESSION['logged'] = true;
	header('location:readMail.php');
} else {
	$_SESSION['logged'] = false;	
	header('location:index.php');
}
?> 