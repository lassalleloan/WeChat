<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 */

require_once('Database.php');
require_once('Role.php');
require_once('User.php');
 
/**
 * Manages user authentication and redirection
 *
 * @author Matthieu Chatelan, Lassalle Loan, Wojciech Myszkorowski
 * @since 27.09.2017
 */
class Authentication {

    private static $_instance;
    private static $_database;
    private static $_user;
    private static $_role;
    
    private function __construct() {

    }

    public static function get_instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
            self::$_database = Database::get_instance();
            self::$_user = User::get_instance();
            self::$_role = Role::get_instance();
        }
        
        return self::$_instance;
    }

    /**
     * Check if the user is authenticated
     */
    public function is_authenticated($username, $password) {

        // Retrieves the credentials of the user
        $credentials = self::$_user->get_credentials_by_username($username);
    
        // Retrieves the account status of the user
        $active = self::$_user->get_active_by_username($username);
    
        // Closes the connection to the database
        self::$_database->deconnection();

        $is_access_granted = isset($credentials) && isset($active);
    
        if ($is_access_granted) {

            // Computes the user's fingerprint
            $temporary_digest = $this->hash_str("{$username}{$credentials['salt']}{$password}");

            // Authorizes and authenticates the user
            $is_access_granted = $credentials['digest'] === $temporary_digest && $active;

            if ($is_access_granted) {
                if (!isset($_SESSION)) {
                    session_start();
                }
                
                $_SESSION['digest'] = $temporary_digest;
            }
        }

        return $is_access_granted;
    }

    /**
     * Check if the user is logged
     */
    public function is_logged() {
        if (!isset($_SESSION)) {
            session_start();
        }

        $is_already_logged = isset($_SESSION['digest']);

        if ($is_already_logged) {
            // Retrieves the credentials of the user
            $session_digest = self::$_user->get_credentials()['digest'];
        
            // Closes the connection to the database
            self::$_database->deconnection();
        }
        
        return $is_already_logged && isset($session_digest);
    }

    /**
     * Check if the user is not logged
     */
    public function is_not_logged() {
        return !$this->is_logged();
    }

    /**
     * Redirect the user if is logged
     */
    public function redirect_if_is_logged() {
        if ($this->is_logged()) {
            header("location:home.php");
            exit();
        }
    }

    /**
     * Redirect the user if is not logged
     */
    public function redirect_if_is_not_logged() {
        if ($this->is_not_logged()) {
            header("location:logout.php");
            exit();
        }
    }

    /**
     * Check if the user is authorized
     */
    public function is_authorized($role) {
        $user_role = self::$_user->get_role();
        self::$_database->deconnection();

        return isset($user_role) && $user_role === $role;
    }

    /**
     * Check if the user is not authorized
     */
    public function is_not_authorized($role) {
        return !$this->is_authorized($role);
    }

    /**
     * Redirect the user if is not authorized
     */
    public function redirect_if_is_not_authorized($role) {
        if ($this->is_not_authorized($role)) {
            header("location:logout.php");
            exit();
        }
    }

    /**
     * Get the footprint of a character string
     */
    public function hash_str($str) {
        return base64_encode(hash('sha512', $str, true));
    }
}
?>
