<?php
require_once('Authentication.php');
require_once('Database.php');
require_once('Mail.php');
require_once('User.php');

Authentication::getInstance()->check();

$row = User::getInstance()->getFields('role')->fetch();
$role = $row['role'] == 1;
         
if ($role) {
    $results = User::getInstance()->getAllRows();  
} else {
    $results = Mail::getInstance()->getAllMail();
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
        <table width="500px">
            <?php
            if ($role) {
                echo '<tr>';
                for ($i = 0; $i < $results->columnCount(); $i++) {
                    $head = $results->getColumnMeta($i);
                    echo '<th>'.ucfirst($head['name']).'</th>';
                }             
                
                while (($row = $results->fetch())) {
                    echo '<tr align="center">';
                    echo '<td>'.$row['username'].'</td>';
                    echo '<td>'.$row['active'].'</td>';
                    echo '<td>'.$row['role'].'</td>';
                    echo '<td>
                          <input type="button" value="Modify" onclick="window.location.href=\'usersManager.php\';">
                          </td>
                          <td>
                          <input type="button" value="Delete" onclick="window.location.href=\'deleteUser.php\';">
                          </td>
                          </tr>';                    
                }
                
            } else {
                echo '<tr>';
                for ($i = 0; $i < $results->columnCount(); $i++) {
                    $head = $results->getColumnMeta($i);
                    echo '<th>'.ucfirst($head['name']).'</th>';
                }
                echo '<th colspan="2">
                      <input type="button" value="New Mail" onclick="window.location.href=\'writeMail.php\';">
                      </th>
                      </tr>';  
                echo '</tr>';               
                
                while (($row = $results->fetch())) {
                    echo '<tr>';
                    echo '<td>'.(new DateTime($row['date'],new DateTimeZone('UTC')))->format('m.d.Y H:i').'</td>';
                    echo '<td>'.$row['username'].'</td>';
                    echo '<td>'.$row['subject'].'</td>';
                    echo '<td>
                          <input type="button" value="More" onclick="window.location.href=\'readMail.php\';">
                          </td>
                          <td>
                          <input type="button" value="Delete" onclick="window.location.href=\'deleteMail.php\';">
                          </td>
                          </tr>';                    
                }
            }
            ?>
        </table>
	</body>
</html>
