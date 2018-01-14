<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 */

extract(@$_GET);
require_once('models/Authentication.php');
require_once('models/Database.php');

// Redirect the user to index.php
Authentication::get_instance()->redirect_if_is_logged();

$is_error = isset($is_error) ? (bool)$is_error : false;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>WeChat - Login</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <h1>Login</h1>
        <br>
		<form method="post" action="controllers/login.php">
			<table>
                <?php
                if ($is_error) {
                    echo '<tr><td colspan="2" align="center" style="color:red; font-weight:bold">Incorrect username or password</td></tr>';
                }
                ?>
				<tr>
                    <th>Username</th>
                    <td><input type="text" name="username" <?php echo 'size="'.Database::USERNAME_MAX.'"'; echo ' minlength="'.Database::USERNAME_MIN.'"'; echo ' maxlength="'.Database::USERNAME_MAX.'"'; ?> required /></td>
				<tr>
					<th>Password</th>
					<td><input type="password" name="password" <?php echo 'size="'.Database::PASSWORD_MAX.'"'; echo ' minlength="'.Database::PASSWORD_MIN.'"'; echo ' maxlength="'.Database::PASSWORD_MAX.'"'; ?> required /></td>
				</tr>
				<tr><td colspan="2" align="right"><input type="submit" value="Login" /></td></tr>
			</table>
		</form>
	</body>
</html>
