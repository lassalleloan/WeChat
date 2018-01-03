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
Authentication::get_instance()->redirect_if_is_not_logged();

$is_error = isset($is_error) ? (bool)$is_error : false;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>WeChat - Password Change</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <h1>Password Change</h1>
        <br>
        <a href="home.php">< Back</a>
        <br>
        <br>
        <form method="post" action="controllers/updatePassword.php">
            <table width="500px">
                <?php
                if ($is_error) {
                    echo '<tr><td colspan="2" align="center" style="color:red; font-weight:bold">Incorrect old password or confirm password</td></tr>';
                }
                ?>
                <tr>
                    <th>Old Password</th>
                    <td><input type="password" name="old_password" <?php echo 'size="'.Database::PASSWORD_MAX.'"'; echo 'minlength="'.Database::PASSWORD_MIN.'"'; echo 'maxlength="'.Database::PASSWORD_MAX.'"'; ?> required /></td>
                </tr>
                <tr>
                    <th>New Password</th>
                    <td><input type="password" name="new_password" <?php echo 'size="'.Database::PASSWORD_MAX.'"'; echo 'minlength="'.Database::PASSWORD_MIN.'"'; echo 'maxlength="'.Database::PASSWORD_MAX.'"'; ?> required /></td>
                </tr>
                <tr>
                    <th>Confirm Password</th>
                    <td><input type="password" name="confirm_password" <?php echo 'size="'.Database::PASSWORD_MAX.'"'; echo 'minlength="'.Database::PASSWORD_MIN.'"'; echo 'maxlength="'.Database::PASSWORD_MAX.'"'; ?> required /></td>
                </tr>
                <tr><td align="right" colspan="2"><input type="submit" value="Done" /></td></tr>
            </table>
        </form>
    </body>
</html>
