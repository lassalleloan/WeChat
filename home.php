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
Authentication::getInstance()->goToLocation(Authentication::getInstance()->isNotLogged());

// Recover received emails
$mails = Mail::getInstance()->getData();
$mail = $mails->fetch();

// Retrieves the role of the user
$role = User::getInstance()->getRole()->fetch()['role'] === '1';

if ($role) {
    
    // Retrieves users
    // depending on the role of the logged in user
    $users = User::getInstance()->getData();
    $user = $users->fetch();
}

// Closes the connection to the database
Database::getInstance()->deconnection();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>WeChat - Home</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    </head>
    <body style="background-image: url(./images/background.jpg)">
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
            if ($mail) {                
                echo '<tr>';
                
                // Displays column headers
                for ($i = 1; $i < $mails->columnCount(); $i++) {
                    echo '<th>'.ucfirst($mails->getColumnMeta($i)['name']).'</th>';
                }
                
                echo '<th colspan="3" >
                    <input type="button" value="New Mail" onclick="window.location.href=\'writeMail.php\';" style="background-color: deepskyblue; font-size: medium;" >
                    </th>
                    </tr>';
                
                // Displays the received emails
                do {
                    echo "<tr align=\"center\">
                        <td>".Utils::getInstance()->dateStrFormat($mail['date'])."</td>
                        <td>{$mail['from']}</td>
                        <td>{$mail['subject']}</td>
                        <td>
                        <input type=\"button\" value=\"More\" onclick=\"window.location.href='readMail.php?id={$mail['id']}';\">
                        </td>
                        <td>
                        <input type=\"button\" value=\"Reply\" onclick=\"window.location.href='writeMail.php?id={$mail['id']}';\">
                        </td>
                        <td>
                        <input type=\"button\" value=\"Delete\" onclick=\"window.location.href='controllers/deleteMail.php?id={$mail['id']}';\">
                        </td>
                        </tr>";
                } while ($mail = $mails->fetch());
            
                if ($role) {
                    echo '<tr>
                        <td colspan="6">
                        <br>
                        </td>
                        </tr>';
                }
            }
            
            if ($role && $user) {
                echo '<tr>';
                
                // Displays column headers
                for ($i = 1; $i < $users->columnCount(); $i++) {
                    echo '<th>'.ucfirst($users->getColumnMeta($i)['name']).'</th>';
                }
                
                echo '<th colspan="3">
                    <input type="button" value="New User" onclick="window.location.href=\'manageUser.php\';">
                    </th>
                    </tr>';
                
                // Displays user information
                do {
                    echo "<tr align=\"center\">
                        <td>{$user['username']}</td>
                        <td>{$user['active']}</td>
                        <td>{$user['role']}</td>
                        <td>
                        <input type=\"button\" value=\"Manage\" onclick=\"window.location.href='manageUser.php?id={$user['id']}';\">
                        </td>
                        <td>
                        </td>
                        <td>
                        <input type=\"button\" value=\"Delete\" onclick=\"window.location.href='controllers/deleteUser.php?id={$user['id']}';\">
                        </td>
                        </tr>";
                } while ($user = $users->fetch());
            }
            ?>
        </table>
	</body>
</html>
