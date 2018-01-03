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
$mails = Mail::get_instance()->get_data();

if (isset($mails)) {
    $mailsHeaders = array_filter(array_keys($mails[0]), function ($value) { return is_string($value); });
    array_shift($mailsHeaders);
}

// Retrieves the role of the user
$isAdministrator = Authentication::get_instance()->is_authorized('Administrator');

if ($isAdministrator) {
    
    // Retrieves users
    // depending on the role of the logged in user
    $users = User::get_instance()->get_data();

    if (isset($users)) {
        $usersHeaders = array_filter(array_keys($users[0]), function ($value) { return is_string($value); });
        array_shift($usersHeaders);
    }
}

// Closes the connection to the database
Database::get_instance()->deconnection();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>WeChat - Home</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <h1>Home</h1>
        <br>
        <a href="controllers/logout.php">Logout</a>
        <br>
        <br>
        <a href="changePassword.php">Change Password</a>
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
                
                echo '<th colspan="3"><input type="button" value="New Mail" onclick="window.location.href=\'writeMail.php\';" /></th></tr>';
                
                // Displays the received emails
                foreach ($mails as $mail) {
                    echo '<tr align="center">
                        <td>'.Utils::get_instance()->date_str_format($mail['date']).'</td>
                        <td>'.$mail['from'].'</td>
                        <td>'.$mail['subject'].'</td>
                        <td><input type="button" value="More" onclick="window.location.href=\'readMail.php?id='.$mail['id'].'\';" /></td>
                        <td><input type="button" value="Reply" onclick="window.location.href=\'writeMail.php?id='.$mail['id'].'\';" /></td>
                        <td><input type="button" value="Delete" onclick="window.location.href=\'controllers/deleteMail.php?id='.$mail['id'].'\';" /></td>
                        </tr>';
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
                
                echo '<th colspan="3"><input type="button" value="New User" onclick="window.location.href=\'manageUser.php\';" /></th></tr>';
                
                // Displays user information
                foreach ($users as $user) {
                    echo '<tr align="center">
                        <td>'.$user['username'].'</td>
                        <td>'.($user['active'] ? 'Yes' : 'No').'</td>
                        <td>'.$user['role'].'</td>
                        <td><input type="button" value="Manage" onclick="window.location.href=\'manageUser.php?id='.$user['id'].'\';" /></td>
                        <td></td>
                        <td><input type="button" value="Delete" onclick="window.location.href=\'controllers/deleteUser.php?id='.$user['id'].'\';" /></td>
                        </tr>';
                }
            }
            ?>
        </table>
	</body>
</html>
