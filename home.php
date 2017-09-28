<?php
require_once('Authentication.php');
require_once('Mail.php');
require_once('User.php');
require_once('Utils.php');

Authentication::getInstance()->toIndex();

$row = User::getInstance()->getRole()->fetch();
$role = $row['role'] == 1;

$resultsMail = Mail::getInstance()->getAll();

if ($role) {
    $resultsUser = User::getInstance()->getAll();
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>WeChat - Home</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    </head>
    <body>
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
            if (($resultsMail->fetch()) > 0) {
                echo '<tr>';
                for ($i = 1; $i < $resultsMail->columnCount(); $i++) {
                    $head = $resultsMail->getColumnMeta($i);
                    echo '<th>'.ucfirst($head['name']).'</th>';
                }
                
                echo '<th colspan="2">
                      <input type="button" value="New Mail" onclick="window.location.href=\'writeMail.php\';">
                      </th>
                      </tr>';  
                echo '</tr>';               
                
                while (($row = $resultsMail->fetch())) {
                    echo '<tr>';
                    echo '<td>'.Utils::getInstance()->strDateFormat($row['date']).'</td>';
                    echo '<td>'.$row['from'].'</td>';
                    echo '<td>'.$row['subject'].'</td>';
                    echo '<td>
                          <input type="button" value="More" onclick="window.location.href=\'readMail.php?id='.$row['id'].'\';">
                          </td>
                          <td>
                          <input type="button" value="Delete" onclick="window.location.href=\'deleteMail.php?id='.$row['id'].'\';">
                          </td>
                          </tr>';                    
                }
            }
            
            if ($role && ($resultsMail->fetch()) > 0) {
                echo '<tr>
                      <td>
                      <br>
                      </td>
                      </tr>
                      <tr>';
            }
            
            if ($role) {
                for ($i = 1; $i < $resultsUser->columnCount(); $i++) {
                    $head = $resultsUser->getColumnMeta($i);
                    echo '<th>'.ucfirst($head['name']).'</th>';
                }
                
                echo '<th colspan="2">
                      <input type="button" value="New User" onclick="window.location.href=\'manageUser.php\';">
                      </th>
                      </tr>';  
                echo '</tr>';     
                
                while (($row = $resultsUser->fetch())) {
                    echo '<tr align="center">';
                    echo '<td>'.$row['username'].'</td>';
                    echo '<td>'.$row['active'].'</td>';
                    echo '<td>'.$row['role'].'</td>';
                    echo '<td>
                          <input type="button" value="Manage" onclick="window.location.href=\'manageUser.php?id='.$row['id'].'\';">
                          </td>
                          <td>
                          <input type="button" value="Unsubscribe" onclick="window.location.href=\'unsubscribeUser.php?id='.$row['id'].'\';">
                          </td>
                          </tr>';                    
                }
            }
            ?>
        </table>
	</body>
</html>
