<?php
require_once('Database.php');
require_once('Mail.php');
require_once('Role.php');
require_once('User.php');

$resultsRoles = Role::getInstance()->getAll();
$resultsUsers = User::getInstance()->getAllUsers();
$resultsMails = Mail::getInstance()->getAllMails();

Database::getInstance()->deconnection();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>WeChat - Display data test</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    </head>
    <body>
        <h1>Display data test</h1>
        <br>
        <h2>Roles</h2>
        <?php
        foreach($resultsRoles as $row) {
            echo 'Name: '.$row['name'].'<br/>';
            echo '<br/>';
        }
        ?>
        <h2>Users</h2>
        <?php
        foreach($resultsUsers as $row) {
            echo 'Username: '.$row['username'].'<br/>';
            echo 'Salt: '.$row['salt'].'<br/>';
            echo 'Digest: '.$row['digest'].'<br/>';
            echo 'Active: '.$row['active'].'<br/>';
            echo 'Role: '.$row['role'].'<br/>';
            echo '<br/>';
        }
        ?>
        <h2>Mails</h2>
        <?php
        foreach($resultsMails as $row) {
            echo 'Date: '.$row['date'].'<br/>';
            echo 'idSender: '.$row['idSender'].'<br/>';
            echo 'idReceiver: '.$row['idReceiver'].'<br/>';
            echo 'Subject: '.$row['subject'].'<br/>';
            echo 'Body: '.$row['body'].'<br/>';
            echo '<br/>';
        }
        ?>
    </body>
</html>