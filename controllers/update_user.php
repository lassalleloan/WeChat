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
require_once(dirname(__DIR__).'/models/User.php');
require_once(dirname(__DIR__).'/models/Role.php');

// Redirect the user to index.php
Authentication::get_instance()->redirect_if_is_not_logged();

// Redirect the user to home.php
Utils::get_instance()->redirect_if_is_not_correct_file_origin(array('manage_user.php'));

$active = isset($active) ? (bool)$active : false;

if (isset($username) && isset($password) && isset($role)) {
    $len_username = strlen($username);
    $is_correct_username = $len_username >= Database::USERNAME_MIN && 
                        $len_username <= Database::USERNAME_MAX;
    
    $len_password = strlen($password);
    $is_correct_password = $len_password >= Database::PASSWORD_MIN && 
                        $len_password <= Database::PASSWORD_MAX;
    
    $len_role = strlen($role);
    $is_correct_role = $len_role >= Database::PHP_STR_MIN && 
                        $len_role <= Database::PHP_STR_MAX &&
                        Role::get_instance()->get_id($role) !== null;

    if ($is_correct_username && $is_correct_role) {
        $id = User::get_instance()->get_id_by_username($username);

        if (isset($id)) {
            User::get_instance()->redirect_if_is_associate_to_user($id);

            // Update the user
            if (empty($password) && empty($confirm_password)) {   

                // Update the user without updating the password
                $user = array('id' => $id,
                            'active' => $active,
                            'role' => $role);
                
                User::get_instance()->update_one($user);
            } else if ($is_correct_password && $password === $confirm_password) {
                
                // Update the user, his password and salt
                $salt = Utils::get_instance()->random_str();
                $digest = Authentication::get_instance()->hash_str("{$username}{$salt}{$password}");
                $user = array('id' => $id,
                            'salt' => $salt,
                            'digest' => $digest,
                            'active' => $active,
                            'role' => $role);
                
                User::get_instance()->update_one($user);
            }
        } else if ($is_correct_password && $password === $confirm_password) {

            // Insert a user
            $user = array('username' => $username,
                        'password' => $password,
                        'active' => $active,
                        'role' => $role);
            
            User::get_instance()->insert_one($user);
        }
    }
}

// Closes the connection to the database
Database::get_instance()->deconnection();

if (isset($user)) {
    header('location:../home.php');
} else if (isset($id)) {
    header('location:../manage_user.php?id='.$id.'&is_error=true');
} else {
    header('location:../manage_user.php?is_error=true');
}
?>
