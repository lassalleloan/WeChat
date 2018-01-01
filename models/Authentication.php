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
    private $_digest;
    
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
     * Get user's digest
     */
    public function get_digest() {
        return $this->_digest;
    }

    /**
     * Check if the user is authenticated
     */
    public function isAuthenticated($username, $password) {

        // Retrieves the credentials of the user
        $credentials = self::$_user->get_credentials_by_username($username);
    
        // Computes the user's fingerprint
        $temporary_digest = $this->hash_str("{$username}{$credentials['salt']}{$password}");
    
        // Retrieves the account status of the user
        $active = self::$_user->get_active_by_username($username);
    
        // Closes the connection to the database
        self::$_database->deconnection();

        // Authorizes and authenticates the user
        $is_access_granted = $credentials['digest'] === $temporary_digest && $active;

        if ($is_access_granted) {
            $this->_digest = $temporary_digest;
        }

        return $is_access_granted;
    }

    /**
     * Check if the user is logged
     */
    public function isLogged() {
        session_start();
        
        return isset($_SESSION['logged']) && $_SESSION['logged'];
    }

    /**
     * Check if the user is not logged
     */
    public function isNotLogged() {
        return !$this->isLogged();
    }

    /**
     * Redirect the user if is logged
     */
    public function redirect_if_is_logged() {
        if ($this->isLogged()) {
            header("location:home.php");
        }
    }

    /**
     * Redirect the user if is not logged
     */
    public function redirect_if_is_not_logged() {
        if ($this->isNotLogged()) {
            header("location:index.php");
        }
    }

    /**
     * Check if the user is authorized
     */
    public function isAuthorized($role) {
        return self::$_user->get_role() === $role;
    }

    /**
     * Check if the user is not authorized
     */
    public function isNotAuthorized($role) {
        return !$this->isAuthorized($role);
    }

    /**
     * Redirect the user if is not authorized
     */
    public function redirect_if_is_not_authorized($role) {
        if ($this->isNotAuthorized($role)) {
            header("location:index.php");
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
