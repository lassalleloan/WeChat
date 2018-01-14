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
require_once('models/Mail.php');
require_once('models/Utils.php');

// Redirect the user to index.php
Authentication::get_instance()->redirect_if_is_not_logged();

$id = isset($id) ? (int)$id : 0;

// Retrieves the user's emails
if ($id >= Database::PHP_INT_MIN && $id <= Database::PHP_INT_MAX) {
    Mail::get_instance()->redirect_if_is_not_associate_to_user($id);
    $mail = Mail::get_instance()->get_by_id($id);

    // Closes the connection to the database
    Database::get_instance()->deconnection();

    if (!isset($mail)) {
        header('location:/wechat/home.php');
        exit;
    }
}

// Generates token for link authentication
$token_mail = Utils::get_instance()->random_str(32);
$_SESSION['token_mail'] = $token_mail;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>WeChat - Read a mail</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/style.css">
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
                    <input type="button" value="Reply" onclick="window.location.href='write_mail.php?id=<?php echo $id; ?>';" />
                    <input type="button" value="Delete" onclick="if(confirm('Do you want to delete this message?')){window.location.href='controllers/delete_mail.php?<?php echo 'id='.$id.'&token='.$token_mail; ?>';}" />
                </td>
            </tr>
            <tr align="left">
                <th width="100px">Date</th>
                <td><?php echo Utils::get_instance()->date_str_format($mail['date']); ?></td>
            </tr>
            <tr align="left">
                <th>From</th>
                <td><?php echo htmlentities($mail['from'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></td>
            </tr>
            <tr align="left">
                <th>To</th>
                <td><?php echo htmlentities($mail['to'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></td>
            </tr>
            <tr align="left">
                <th>Subject</th>
                <td><?php echo htmlentities($mail['subject'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></td>
            </tr>
            <tr><td colspan="2"><br></td></tr>
            <tr align="left"><th colspan="2">Mail Body</th></tr>
            <tr>
                <td></td>
                <td><textarea id="mailBody" cols="52" rows="20" maxlength="1000" style="border: none; resize: none;" readonly required><?php echo htmlentities($mail['body'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></textarea></td>
            </tr>
        </table>
    </body>
</html>
