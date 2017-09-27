<?php
require_once('Database.php');

session_start();
extract(@$_POST);

$row = Database::getInstance()->query('SELECT salt,
                                        digest AS digest
                                        FROM users
                                        WHERE username="'.$username.'";')->fetch();
$digestComp = base64_encode(hash('sha512', $username.$row['salt'].$password, true));
$_SESSION['logged'] = $row['digest'] === $digestComp;

if ($_SESSION['logged']) {
    $_SESSION['digest'] = $digestComp;
	header('location:home.php');
} else {	
	header('location:index.php');
}
?> 
