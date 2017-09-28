<?php
require_once('Authentication.php');

Authentication::getInstance()->toIndex();
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
        <form method="post" action="changePassword.php">
            <table width="500px">
                <tr>
                    <td>
                        Old Password
                    </td>
                    <td>
                        <input type="password" name="oldPassword" size="50" minlength="8" maxlength="50" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        New Password
                    </td>
                    <td>
                        <input type="password" name="newPassword" size="50" minlength="8" maxlength="50" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        Confirm Password
                    </td>
                    <td>
                        <input type="password" name="confirmPassword" size="50" minlength="8" maxlength="50" required>
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td align="right">
                        <input type="submit" value="Done">
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>
