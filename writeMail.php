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

// Redirect the user to index.php
Authentication::get_instance()->redirect_if_is_not_logged();

$value_form = User::get_instance()->get_username();

if (isset($value_form)) {
    $id_mail = '';
    $value_form = 'value="'.$value_form.'"';
    $style_form = 'style="border:none" readonly ';
    $value_to = '';
    $value_subject = '';
    $style_others = '';
    
    $id = isset($id) ? (int)$id : 0;
    $is_correct_id = $id >= Database::PHP_INT_MIN && $id <= Database::PHP_INT_MAX;
    
    // If it's the answer to an email
    if ($is_correct_id) {
        Mail::get_instance()->redirect_if_is_not_associate_to_user($id);
    
        $subject = Mail::get_instance()->get_subject($id);
        $value_to = Mail::get_instance()->get_to($id);
    
        if (isset($subject) && isset($value_to)) {
            $subject = substr($subject, 0, 4) === 'RE: ' ? $subject : 'RE: '.$subject;
    
            $id_mail = "?id={$id}";    
            $value_to = 'value="'.$value_to.'"';
            $value_subject = 'value="'.$subject.'"';
            $style_others = 'style="border:none" readonly ';
        }
    }
}

if (!isset($value_form) || ($is_correct_id && (!isset($subject) || !isset($value_to)))) {

    // Closes the connection to the database
    Database::get_instance()->deconnection();

    header('location:home.php');
    exit;
}

$is_error = isset($is_error) ? (bool)$is_error : false;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>WeChat - Write a mail</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <h1>Write a mail</h1>
        <br>
        <a href="home.php">< Back</a>
        <br>
        <br>
        <form method="post" action="controllers/sendMail.php<?php echo $id_mail; ?>">
            <table width="500px">
                <?php
                if ($is_error) {
                    echo '<tr><td colspan="2" align="center" style="color:red; font-weight:bold">Unknown username</td></tr>';
                }
                ?>
                <tr align="right"><td colspan="2"><input type="submit" value="Send" /></td></tr>
                <tr align="left">
                    <th width="100px">From</th>
                    <td><input type="text" name="from" size="50" minlength="3" maxlength="50" <?php echo $value_form; echo $style_form; ?> required /></td>
                </tr>
                <tr align="left">
                    <th>To</th>
                    <td><input type="text" name="to" size="50" minlength="3" maxlength="50" <?php echo $value_to; echo $style_others; ?> required /></td>
                </tr>
                <tr align="left">
                    <th>Subject</th>
                    <td><input type="text" name="subject" size="50" minlength="3" maxlength="50" <?php echo $value_subject; echo $style_others; ?> required /></td>
                </tr>
                <tr><td colspan="2"><br></td></tr>
                <tr align="left"><th colspan="2">Mail Body</th></tr>
                <tr>
                    <td></td>
                    <td><textarea name="body" cols="52" rows="20" minlength="3" maxlength="1000" style="resize: none;" required></textarea></td>
                </tr>
            </table>
        </form>
    </body>
</html>
