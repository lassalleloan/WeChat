<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 */

date_default_timezone_set('Europe/Zurich');
extract(@$_GET);
extract(@$_POST);
require_once(dirname(__DIR__).'/models/Authentication.php');
require_once(dirname(__DIR__).'/models/Database.php');
require_once(dirname(__DIR__).'/models/Mail.php');
require_once(dirname(__DIR__).'/models/User.php');

// Redirect the user to index.php
Authentication::get_instance()->redirect_if_is_not_logged();

if (isset($from) && isset($to) && isset($subject) && isset($body)) {
    $len_from = strlen($from);
    $is_correct_from = $len_from >= Database::USERNAME_MIN && 
                    $len_from <= Database::USERNAME_MAX;
                            
    $len_to = strlen($to);
    $is_correct_to = $len_to >= Database::USERNAME_MIN && 
                    $len_to <= Database::USERNAME_MAX;

    $len_subject = strlen($subject);
    $is_correct_subject = $len_subject >= Database::PHP_STR_MIN && 
                    $len_subject <= Database::PHP_STR_MAX;
                            
    $len_body = strlen($body);
    $is_correct_body = $len_body >= Database::PHP_TEXT_MIN && 
                    $len_body <= Database::PHP_TEXT_MAX;

    if ($is_correct_from &&  $is_correct_to && $is_correct_subject && $is_correct_body) {
        $username = User::get_instance()->get_username();

        if (isset($username) &&
            $username === $from &&
            User::get_instance()->get_id_by_username($to) !== null) {

            $len_subject = strlen($subject);
            $is_correct_subject = $len_subject >= Database::PHP_STR_MIN && 
                                $len_subject <= Database::PHP_STR_MAX;
                                    
            $len_body = strlen($body);
            $is_correct_body = $len_body >= Database::PHP_TEXT_MIN && 
                            $len_body <= Database::PHP_TEXT_MAX;

            if ($is_correct_subject && $is_correct_body) {
            
                // Insert an email
                $mail = array('date' => substr(date('Y-m-d\TH:i:s.u'), 0, Database::PHP_DATE_LEN),
                            'sender' => $from,
                            'receiver' => $to,
                            'subject' => $subject,
                            'body' => $body);
                
                Mail::get_instance()->insert_one($mail);
            }
        }
    }
}

// Closes the connection to the database
Database::get_instance()->deconnection();

// Redirect the user
if (isset($mail)) {
    header('location:../home.php');
} else if (isset($id)) {
    header('location:../writeMail.php?id='.$id.'&is_error=true');
} else {
    header('location:../writeMail.php?is_error=true');
}
?>
