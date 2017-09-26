<?php
require ('classes/Authentication.php');

Authentication::check();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>WeChat - Users manager</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    </head>
    <body>
        <h1>Users manager</h1>
        <br>
        <a href="home.php">< Back</a>
        <br>
        <br>
        <form method="post" action="usersManager.php">
            <table width="500px">
                <tr>
                    <td colspan="2">
                        <select name="usersList" id="usersList" onchange="submit();">
                            <option value="0" selected>Add a user</option>
                            <option value="1">User_01</option>
                            <option value="2">User_02</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <br>
                    </td>
                </tr>
                <tr align="left">
                    <th>
                        Username
                    </th>
                    <td>
                        <input type="text" name="usersUsername" size="50" minlength="1" maxlength="50" required>
                    </td>
                </tr>
                <tr align="left">
                    <th>
                        New Password
                    </th>
                    <td>
                        <input type="password" name="userNewPassword" size="50" minlength="8" maxlength="50" required>
                    </td>
                </tr>
                <tr align="left">
                    <th>
                        Confirm Password
                    </th>
                    <td>
                        <input type="password" name="userConfirmPassword" size="50" minlength="8" maxlength="50" required>
                    </td>
                </tr>
                <tr align="left">
                    <th>
                        Active
                    </th>
                    <td>
                        <input type="radio" name="userActive" value="True" checked required>True<br>
                        <input type="radio" name="userActive" value="False" required>False<br>
                    </td>
                </tr>
                <tr align="left">
                    <th>
                        Type of account
                    </th>
                    <td>
                        <input type="radio" name="userRole" value="Administrator" required>Administrator<br>
                        <input type="radio" name="userRole" value="Co-worker" checked required>Co-worker<br>
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