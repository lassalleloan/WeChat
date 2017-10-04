<?php
/**
 * STI - Project Web
 * WeChat
 * Description: web site to send mails between users 
 * Authors: Loan Lassalle, Wojciech Myszkorowski
 */

extract(@$_GET);
require_once('Authentication.php');

// Redirige l'utilisateur vers index.php
Authentication::getInstance()->goToLocation(Authentication::getInstance()->isNotLogged());
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>WeChat - Password Change</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    </head>
    <body>
        <h1>Password Change</h1>
        <br>
        <a href="home.php">< Back</a>
        <br>
        <br>
        <form method="post" action="updatePassword.php">
            <table width="500px">
                <?php
                if (isset($error) && $error) {
                    echo '<tr>
                        <td colspan="2" align="center" style="color:red; font-weight:bold">
                        Incorrect old password or confirm password
                        </td>
                        </tr>';
                }
                ?>
                <tr>
                    <th>
                        Old Password
                    </th>
                    <td>
                        <input type="password" name="oldPassword" size="50" minlength="8" maxlength="50" required>
                    </td>
                </tr>
                <tr>
                    <th>
                        New Password
                    </th>
                    <td>
                        <input type="password" name="newPassword" size="50" minlength="8" maxlength="50" required>
                    </td>
                </tr>
                <tr>
                    <th>
                        Confirm Password
                    </th>
                    <td>
                        <input type="password" name="confirmPassword" size="50" minlength="8" maxlength="50" required>
                    </td>
                </tr>
                <tr>
                    <td align="right" colspan="2">
                        <input type="submit" value="Done">
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>
