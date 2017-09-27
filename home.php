<?php
require_once('Authentication.php');
require_once('Database.php');

Authentication::check();

// TODO: cacher requete SQL, formater date, liens vers détais ou suppresion
$database = new Database;
$results = $database->query('SELECT role FROM users
                                    WHERE digest="'.$_SESSION['digest'].'";');
$role = $results->fetch();
$role = $role['role'] == 1;
         
if ($role) {
    $results = $database->query('SELECT username AS Username, 
                                        active AS Active, 
                                        role AS Role
                                        FROM users 
                                        INNER JOIN roles ON users.id = roles.id;');    
} else {
    $results = $database->query('SELECT date AS Date, 
                                        username AS Username, 
                                        subject AS Subject
                                        FROM mails 
                                        INNER JOIN users ON mails.idSender = users.id 
                                        WHERE idReceiver=(SELECT id FROM users WHERE digest="'.$_SESSION['digest'].'");');
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
        <table width="500px">
            <?php
            if ($role) {
                echo '<tr>';
                for ($i = 0; $i < $results->columnCount(); $i++) {
                    $head = $results->getColumnMeta($i);
                    echo '<th>'.$head['name'].'</th>';
                }
                echo '</tr>';               
                
                while (($row = $results->fetch())) {
                    echo '<tr align="center">';
                    echo '<td>'.$row['Username'].'</td>';
                    echo '<td>'.$row['Active'].'</td>';
                    echo '<td>'.$row['Role'].'</td>';
                    echo '<td>
                             <input type="button" value="Modify" onclick="window.location.href=\'usersManager.php\';">
                          </td>';
                    echo '<td>
                             <input type="button" value="Delete" onclick="window.location.href=\'deleteUser.php\';">
                          </td>';
                    echo '</tr>';                    
                }
                
            } else {
                echo '<tr>';
                for ($i = 0; $i < $results->columnCount(); $i++) {
                    $head = $results->getColumnMeta($i);
                    echo '<th>'.$head['name'].'</th>';
                }
                echo '</tr>';               
                
                while (($row = $results->fetch())) {
                    echo '<tr>';
                    echo '<td>'.$row['Date'].'</td>';
                    echo '<td>'.$row['Username'].'</td>';
                    echo '<td>'.$row['Subject'].'</td>';
                    echo '<td>
                             <input type="button" value="More" onclick="window.location.href=\'readMail.php\';">
                          </td>';
                    echo '<td>
                             <input type="button" value="Delete" onclick="window.location.href=\'deleteMail.php\';">
                          </td>';
                    echo '</tr>';                    
                }
            }
            ?>
        </table>
	</body>
</html>
