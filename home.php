<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 */

require_once('models/Authentication.php');
require_once('models/Database.php');
require_once('models/Mail.php');
require_once('models/User.php');
require_once('models/Utils.php');

// Redirect the user to index.php
Authentication::get_instance()->redirect_if_is_not_logged();

// Recover received emails
$mailsHeaders = Mail::get_instance()->get_data_headers();
$mails = Mail::get_instance()->get_data();

// Retrieves the role of the user
$isAdministrator = Authentication::get_instance()->is_authorized('Administrator');

if ($isAdministrator) {
    
    // Retrieves users
    // depending on the role of the logged in user
    $usersHeaders = User::get_instance()->get_data_headers();
    $users = User::get_instance()->get_data();
}

// Closes the connection to the database
Database::get_instance()->deconnection();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>WeChat - Home</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <h1>Home</h1>
        <br>
        <a href="controllers/logout.php">Logout</a>
        <br>
        <br>
        <a href="change_password.php">Change Password</a>
        <br>
        <br>
        <table width="500px">
            <?php
            if (isset($mailsHeaders)) {                
                echo '<tr>';

                // Displays column headers
                foreach ($mailsHeaders as $headerName) {
                    echo '<th>'.ucfirst($headerName).'</th>';
                }
                
                echo '<th colspan="3"><input type="button" value="New Mail" onclick="window.location.href=\'write_mail.php\';" /></th></tr>';
                
                if (isset($mails)) {

                    // Displays the received emails
                    foreach ($mails as $mail) {
                        echo '<tr align="center">
                            <td>'.Utils::get_instance()->date_str_format($mail['date']).'</td>
                            <td>'.htmlentities($mail['from'], ENT_QUOTES | ENT_HTML5, 'UTF-8').'</td>
                            <td>'.htmlentities($mail['subject'], ENT_QUOTES | ENT_HTML5, 'UTF-8').'</td>
                            <td><input type="button" value="More" onclick="window.location.href=\'read_mail.php?id='.$mail['id'].'\';" /></td>
                            <td><input type="button" value="Reply" onclick="window.location.href=\'write_mail.php?id='.$mail['id'].'\';" /></td>
                            <td><input type="button" value="Delete" onclick="if(confirm(\'Do you want to delete this message?\')){window.location.href=\'controllers/delete_mail.php?id='.$mail['id'].'\';}" /></td>
                            </tr>';
                    }
                }
            
                if ($isAdministrator) {
                    echo '<tr><td colspan="6"><br></td></tr>';
                }
            }
            
            if (isset($usersHeaders)) {
                echo '<tr>';

                // Displays column headers
                foreach ($usersHeaders as $headerName) {
                    echo '<th>'.ucfirst($headerName).'</th>';
                }
                
                echo '<th colspan="3"><input type="button" value="New User" onclick="window.location.href=\'manage_user.php\';" /></th></tr>';
                
                if (isset($users)) {
                
                    // Displays user information
                    foreach ($users as $user) {
                        echo '<tr align="center">
                            <td>'.htmlentities($user['username'], ENT_QUOTES | ENT_HTML5, 'UTF-8').'</td>
                            <td>'.($user['active'] ? 'Yes' : 'No').'</td>
                            <td>'.htmlentities($user['role'], ENT_QUOTES | ENT_HTML5, 'UTF-8').'</td>
                            <td><input type="button" value="Manage" onclick="window.location.href=\'manage_user.php?id='.$user['id'].'\';" /></td>
                            <td></td>
                            <td><input type="button" value="Delete" onclick="if(confirm(\'Do you want to delete this user?\')){window.location.href=\'controllers/delete_user.php?id='.$user['id'].'\';}" /></td>
                            </tr>';
                    }
                }
            }
            ?>
        </table>
	</body>
</html>
