<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 */

extract(@$_POST);
require_once(dirname(__DIR__).'/models/Authentication.php');
require_once(dirname(__DIR__).'/models/Database.php');
require_once(dirname(__DIR__).'/models/HttpRequest.php');
require_once(dirname(__DIR__).'/models/User.php');
require_once(dirname(__DIR__).'/models/PasswordMeter.php');

// Redirect the user to index.php
Authentication::get_instance()->redirect_if_is_not_logged();

// Redirect the user to home.php
Utils::get_instance()->redirect_if_is_not_correct_file_origin(array('change_password.php'));

if (isset($old_password) && isset($new_password) && isset($confirm_password)) {
    $len_old_password = strlen($old_password);
    $is_correct_old_password = $len_old_password >= Database::PASSWORD_MIN && 
                            $len_old_password <= Database::PASSWORD_MAX;

    $len_new_password = strlen($new_password);
    $is_correct_new_password = $len_new_password >= Database::PASSWORD_MIN && 
                            $len_new_password <= Database::PASSWORD_MAX;

    $len_confirm_password = strlen($confirm_password);
    $is_correct_confirm_password = $len_confirm_password >= Database::PASSWORD_MIN && 
                                $len_confirm_password <= Database::PASSWORD_MAX;

    // Check inputs
    if ($is_correct_old_password && $is_correct_new_password && $is_correct_confirm_password) {

        // Retrieves user name, salt and user's fingerprint
        $username = User::get_instance()->get_username();

        if (isset($username)) {
            $salt = User::get_instance()->get_credentials_by_username($username)['salt'];

            if (isset($salt)) {
                $old_digest = Authentication::get_instance()->hash_str("{$username}{$salt}{$old_password}");

                // Authenticates the user
                if ($_SESSION['digest'] === $old_digest && $new_password === $confirm_password) {
                    $salt = Utils::get_instance()->random_str();
                    $new_digest = Authentication::get_instance()->hash_str("{$username}{$salt}{$new_password}");
                
                    User::get_instance()->update_salt($salt);
                    User::get_instance()->update_digest($new_digest);
                }
            }
        }
    }
}

// Closes the connection to the database
Database::get_instance()->deconnection();

if (isset($new_digest)) {
    $data = array(
        'app' => 'mail',
        'type' => 'login',
        'properties' => array(
            'username' => $username
        )
    );

    $data['properties']['strength'] = PasswordMeter::get_instance()->get_strength($username, $new_password);
    $request = new HttpRequest('post', 'https://stormy-hamlet-80891.herokuapp.com/event', $data);
    
    if ($request->getError()) {
        error_log('POST request does not work properly');
    } else {
        error_log('HttpRequest POST data: '.http_build_query($data));
        error_log('HttpRequest POST response: '.http_build_query($request));
    }

    header('location:logout.php');
} else {
    header('location:/wechat/change_password.php?is_error=true');
}
?>
