<?php
/**************************************************
* STI - Project Web
* WeChat
* Description: web site to sen mails between users 
* Authors: Loan Lassalle, Wojciech Myszkorowski
**************************************************/

require_once('Authentication.php');
require_once('Mail.php');
require_once('User.php');
require_once('Utils.php');

Authentication::getInstance()->goToLocation(Authentication::getInstance()->isNotLogged());

$mails = Mail::getInstance()->getData();
$mail = $mails->fetch();
$role = User::getInstance()->getRole()->fetch()['role'] === '1';

if ($role) {
    $users = User::getInstance()->getData();
    $user = $users->fetch();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>WeChat - Home</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    </head>
    <body style="background-image: url(./fond2.jpg)">
        <h1>Home</h1>
        <br>
        <a href="logout.php">Logout</a>
        <br>
        <br>
        <a href="changePassword.php">Change Password</a>
        <br>
        <br>
        <table width="500px">
            <?php
            if ($mail) {                
                echo '<tr>';
                
                for ($i = 1; $i < $mails->columnCount(); $i++) {
                    echo '<th>'.ucfirst($mails->getColumnMeta($i)['name']).'</th>';
                }
                
                echo '<th colspan="2">
                    <input type="button" value="New Mail" onclick="window.location.href=\'writeMail.php\';">
                    </th>
                    </tr>';
                
                do {
                    echo "<tr align=\"center\">
                        <td>".Utils::getInstance()->dateStrFormat($mail['date'])."</td>
                        <td>{$mail['from']}</td>
                        <td>{$mail['subject']}</td>
                        <td>
                        <input type=\"button\" value=\"More\" onclick=\"window.location.href='readMail.php?id={$mail['id']}';\">
                        </td>
                        <td>
                        <input type=\"button\" value=\"Delete\" onclick=\"window.location.href='deleteMail.php?id={$mail['id']}';\">
                        </td>
                        </tr>";
                } while ($mail = $mails->fetch());
            
                if ($role) {
                    echo '<tr>
                        <td>
                        <br>
                        </td>
                        </tr>';
                }
            }
            
            if ($role && $user) {
                echo '<tr>';
                
                for ($i = 1; $i < $users->columnCount(); $i++) {
                    echo '<th>'.ucfirst($users->getColumnMeta($i)['name']).'</th>';
                }
                
                echo '<th colspan="2">
                    <input type="button" value="New User" onclick="window.location.href=\'manageUser.php\';">
                    </th>
                    </tr>';
                
                do {
                    echo "<tr align=\"center\">
                        <td>{$user['username']}</td>
                        <td>{$user['active']}</td>
                        <td>{$user['role']}</td>
                        <td>
                        <input type=\"button\" value=\"Manage\" onclick=\"window.location.href='manageUser.php?id={$user['id']}';\">
                        </td>
                        <td>
                        <input type=\"button\" value=\"Unsubscribe\" onclick=\"window.location.href='unsubscribeUser.php?id={$user['id']}';\">
                        </td>
                        </tr>";
                } while ($user = $users->fetch());
            }
            ?>
        </table>
	</body>
</html>
