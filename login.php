<?php
require_once('Database.php');

session_start();
extract(@$_POST);

$database = new Database;
$result = $database->query('SELECT id, salt, digest FROM users WHERE username="'.$username.'";');
$result = $result->fetch();
$digest = base64_encode(hash('sha512', $username.$result['salt'].$password, true));
$_SESSION['logged'] = $result['digest'] === $digest;

if ($_SESSION['logged']) {
    $_SESSION['idUser'] = $result['id'];
    $_SESSION['digest'] = $digest;
	header('location:home.php');
    
    // if (isset($rememberMe)) {
        // /* Set cookie to last 1 year */
        // setcookie('username', $_POST['username'], time()+60*60*24*365, '/account', 'www.ex.com');
        // setcookie('password', md5($_POST['password']), time()+60*60*24*365, '/account', 'www.ex.com');

    // } else {
        // /* Cookie expires when browser closes */
        // setcookie('username', $_POST['username'], false, '/account', 'www.ex.com');
        // setcookie('password', md5($_POST['password']), false, '/account', 'www.ex.com');
    // }
} else {	
	header('location:index.php');
}
?> 
