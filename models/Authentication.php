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
    private static $_token;
    
    private function __construct() {

    }

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
            self::$_database = Database::getInstance();
            self::$_user = User::getInstance();
            self::$_role = Role::getInstance();
        }
        
        return self::$_instance;
    }

    /**
     * Get user's token
     */
    public function getToken() {
        return self::$_token;
    }

    /**
     * Check if the user is authenticated
     */
    public function isAuthenticated($username, $password) {

        // Retrieves the credentials of the user
        $credentials = self::$_user->getCredentialsByUsername($username)->fetch();
    
        // Computes the user's fingerprint
        self::$_token = self::hashStr("{$username}{$credentials['salt']}{$password}");
    
        // Retrieves the account status of the user
        $active = self::$_user->getActiveByUsername($username)->fetch()['active'];
    
        // Closes the connection to the database
        self::$_database->deconnection();

        // Authorizes and authenticates the user
        return $credentials['digest'] === self::$_token && $active;
    }

    /**
     * Redirect the user if is logged
     */
    public function redirectIfIsLogged() {
        if (self::isLogged()) {
            header("location:home.php");
        }
    }

    /**
     * Redirect the user if is not logged
     */
    public function redirectIfIsNotLogged() {
        if (!self::isLogged()) {
            header("location:index.php");
        }
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
        return !self::isLogged();
    }

    public function isAuthorized($role) {
        $userRole = self::$_role->getName(self::$_user->getRole()->fetch()['role']);
        return $userRole === $role;
    }

    public function isNotAuthorized() {
        return !self::isAuthorized();
    }

    /**
     * Get the footprint of a character string
     */
    private function hashStr($str) {
        return base64_encode(hash('sha512', $str, true));
    }
}
?>