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
require_once('models/User.php');

// Redirect the user to index.php
Authentication::get_instance()->redirect_if_is_not_logged();
Authentication::get_instance()->redirect_if_is_not_authorized('Administrator');

$value_username = '';
$style_username = '';
    
$active_checked = 'checked';

$role_administrator = '';
$role_co_worker = 'checked';

$id = isset($id) ? (int)$id : 0;

// If a user is selected
if ($id >= Database::PHP_INT_MIN && $id <= Database::PHP_INT_MAX) {
    $user = User::get_instance()->get_user_by_id($id);

    // Closes the connection to the database
    Database::get_instance()->deconnection();
    
    if (isset($user)) {
        $value_username = 'value="'.$user['username'].'"';
        $style_username = 'style="border:none" readonly';
        
        if (!$user['active']) {
            $active_checked = '';
        }

        if ($user['role'] === 'Administrator') {
            $role_administrator = 'checked';
            $role_co_worker = '';
        }
    } else {
        header('location:home.php');
        exit;
    }
}

$is_error = isset($is_error) ? (bool)$is_error : false;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>WeChat - Users manager</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <h1>Users manager</h1>
        <br>
        <a href="home.php">< Back</a>
        <br>
        <br>
        <form method="post" action="controllers/updateUser.php">
            <table width="500px">
                <?php
                if ($is_error) {
                    echo '<tr><td colspan="2" align="center" style="color:red; font-weight:bold">Incorrect confirm password</td></tr>';
                }
                ?>
                <tr><td colspan="2"><br></td></tr>
                <tr align="left">
                    <th>Username</th>
                    <td><input type="text" name="username" <?php echo 'size="'.Database::USERNAME_MAX.'"'; echo 'minlength="0"'; echo 'maxlength="'.Database::USERNAME_MAX.'"'; echo $value_username; echo $style_username; ?> /></td>
                </tr>
                <tr align="left">
                    <th>Password</th>
					<td><input type="password" name="password" <?php echo 'size="'.Database::PASSWORD_MAX.'"'; echo 'minlength="0"'; echo 'maxlength="'.Database::PASSWORD_MAX.'"'; ?> /></td>
                </tr>
                <tr align="left">
                    <th>Confirm Password</th>
                    <td><input type="password" name="confirm_password" <?php echo 'size="'.Database::PASSWORD_MAX.'"'; echo 'minlength="0"'; echo 'maxlength="'.Database::PASSWORD_MAX.'"'; ?> /></td>
                </tr>
                <tr align="left">
                    <th>Active</th>
                    <td><input type="checkbox" name="active" value="true" <?php echo $active_checked?> /><br></td>
                </tr>
                <tr align="left">
                    <th>Type of account</th>
                    <td>
                        <input type="radio" name="role" value="Administrator" <?php echo $role_administrator; ?> />Administrator<br>
                        <input type="radio" name="role" value="Co-worker" <?php echo $role_co_worker; ?> />Co-worker<br>
                    </td>
                </tr>
                <tr><td align="right" colspan="2"><input type="submit" value="Done" /></td></tr>
            </table>
        </form>
    </body>
</html>
