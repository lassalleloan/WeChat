<?php
require_once('Authentication.php');
require_once('User.php');
extract(@$_GET);

Authentication::getInstance()->check();
$row = User::getInstance()->getFields('username')->fetch();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>WeChat - Write a mail</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    </head>
    <body>
        <h1>Write a mail</h1>
        <br>
        <a href="home.php">< Back</a>
        <br>
        <br>
        <form method="post" action="sendMail.php">
            <table width="500px">
                <tr align="right">
                    <td colspan="2">
                        <input type="submit" value="Send">
                    </td>
                </tr>
                <tr align="left">
                    <th width="100px">
                        From
                    </th>
                    <td>
                        <input type="text" name="from" size="51" maxlength="50" value="<?php echo $row['username']; ?>" style="border:none" readonly required>
                    </td>
                </tr>
                <tr align="left">
                    <th>
                        To
                    </th>
                    <td>
                        <input type="text" name="to" size="51" maxlength="50" <?php if (isset($to)) {echo 'value="'.$to.'"'; echo ' style="border:none" readonly"';} ?> required>
                    </td>
                </tr>
                <tr align="left">
                    <th>
                        Subject
                    </th>
                    <td>
                        <input type="text" name="subject" size="51" maxlength="50" <?php if (isset($subject)) {echo 'value="RE:'.$subject.'"'; echo ' style="border:none" readonly"';} ?> required>
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
                        <textarea name="body" cols="52" rows="20" maxlength="1000" style="resize: none;" required></textarea>
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>
