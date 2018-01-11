<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 */

// Parameter of the time zone
date_default_timezone_set('Europe/Zurich');

extract(@$_POST);
require_once(dirname(__DIR__).'/models/Authentication.php');
require_once(dirname(__DIR__).'/models/Blacklist.php');
require_once(dirname(__DIR__).'/models/Database.php');
require_once(dirname(__DIR__).'/models/Utils.php');

Utils::get_instance()->redirect_if_is_not_correct_file_origin(array('index.php'));

// Blacklist the user
$attempt = Blacklist::get_instance()->get_attempt();
$is_blacklisted = isset($attempt) && $attempt >= Blacklist::ATTEMPTS_MAX;

if (!$is_blacklisted && isset($username) && isset($password)) {
    $len_username = strlen($username);
    $is_correct_username = $len_username >= Database::USERNAME_MIN && 
                            $len_username <= Database::USERNAME_MAX;
                            
    $len_password = strlen($password);
    $is_correct_password = $len_password >= Database::PASSWORD_MIN && 
                            $len_password <= Database::PASSWORD_MAX;
    
    // Authenticates the user
    if ($is_correct_username && $is_correct_password) {
        if (isset($attempt)) {
            Blacklist::get_instance()->increment_attempt();
        } else {
            Blacklist::get_instance()->set();
        }

        $is_error = !Authentication::get_instance()->is_authenticated($username, $password);
    }
}

// Redirect the user after authentication
if (isset($is_error) && $is_error) {
    header('location:/wechat/index.php?is_error=true');
} else if ($is_blacklisted) {
    Blacklist::get_instance()->max_attempt();
    header('location:/wechat/errors/attempts.html');
} else {
    Blacklist::get_instance()->reset();
    header('location:/wechat/home.php');
}
?> 
