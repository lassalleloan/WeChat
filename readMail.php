<?php
require_once('Authentication.php');

Authentication::getInstance()->check();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>WeChat - Read a mail</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    </head>
    <body>
        <h1>Read a mail</h1>
        <br>
        <a href="home.php">< Back</a>
        <br>
        <br>
        <table width="500px">
            <tr align="right">
                <td colspan="2">
                    <input type="button" value="Reply" onclick="window.location.href='writeMail.php';">
                    <input type="button" value="Delete" onclick="window.location.href='deleteMail.php';">
                </td>
            </tr>
            <tr align="left">
                <th width="100px">
                    Date
                </th>
                <td>
                    12.12.2017
                </td>
            </tr>
            <tr align="left">
                <th>
                    From
                </th>
                <td>
                    Loan Lassalle
                </td>
            </tr>
            <tr align="left">
                <th>
                    To
                </th>
                <td>
                    Wojciech Myszkorowski
                </td>
            </tr>
            <tr align="left">
                <th>
                    Subject
                </th>
                <td>
                    RootMe - Inscription
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br>
                </td>
            </tr>
            <tr align="left">
                <th colspan="2">
                    Mail Body
                </th>
            </tr>
            <tr>
                <td>
                </td>
                <td>
                    <textarea id="mailBody" cols="52" rows="20" maxlength="1000" style="border: none; resize: none;" readonly required>Welcome to RootMe !!</textarea>
                </td>
            </tr>
        </table>
    </body>
</html>
