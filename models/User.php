<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 **/

require_once('Authentication.php');
require_once('Database.php');
require_once('Mail.php');
require_once('Parameter.php');
require_once('Utils.php');

/**
 * Manages users
 *
 * @author Matthieu Chatelan, Lassalle Loan, Wojciech Myszkorowski
 * @since 27.09.2017
 */
class User {

    private static $_instance;
    private static $_authentication;
    private static $_database;
    private static $_mail;
    private static $_utils;
    
    private function __construct() {

    }

    public static function get_instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
            self::$_authentication = Authentication::get_instance();
            self::$_database = Database::get_instance();
            self::$_mail = Mail::get_instance();
            self::$_utils = Utils::get_instance();
        }
        
        return self::$_instance;
    }
    
    /**
     * Retrieves user's ID with digest
     */
    public function get_id() {
        $query = "SELECT id 
                        FROM users 
                        WHERE digest=:digest;";
        $parameters = array(new Parameter(':digest', $_SESSION['digest'], PDO::PARAM_STR));
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0]['id'] : null;
    }
    
    /**
     * Retrieves a user's ID with username
     */
    public function get_id_by_username($username) {
        $query = "SELECT id 
                        FROM users 
                        WHERE username=:username;";
        $parameters = array(new Parameter(':username', $username, PDO::PARAM_STR));
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0]['id'] : null;
    }
    
    /**
     * Retrieves user's username with digest
     */
    public function get_username() {
        $query = "SELECT username 
                        FROM users 
                        WHERE digest=:digest;";
        $parameters = array(new Parameter(':digest', $_SESSION['digest'], PDO::PARAM_STR));
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0]['username'] : null;
    }
    
    /**
     * Retrieves user's credentials with digest
     */
    public function get_credentials() {
        $query = "SELECT salt, 
                        digest 
                        FROM users 
                        WHERE digest=:digest;";
        $parameters = array(new Parameter(':digest', $_SESSION['digest'], PDO::PARAM_STR));
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0] : null;
    }
    
    /**
     * Retrieves user's credentials with username
     */    
    public function get_credentials_by_username($username) {
        $query = "SELECT salt, 
                        digest 
                        FROM users 
                        WHERE username=:username;";
        $parameters = array(new Parameter(':username', $username, PDO::PARAM_STR));
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0] : null;
    }
    
    /**
     * Retrieves user's role with digest
     */
    public function get_role() {
        $query = "SELECT name AS role 
                        FROM users 
                        INNER JOIN roles ON users.role = roles.id 
                        WHERE digest=:digest;";
        $parameters = array(new Parameter(':digest', $_SESSION['digest'], PDO::PARAM_STR));
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0]['role'] : null;
    }
    
    /**
     * Retrieves user's role with username
     */
    public function get_role_by_username($username) {
        $query = "SELECT name AS role 
                        FROM users 
                        INNER JOIN roles ON users.role = roles.id 
                        WHERE username=:username;";
        $parameters = array(new Parameter(':username', $username, PDO::PARAM_STR));
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0]['role'] : null;
    }
    
    /**
     * Retrieves user's status with digest
     */
    public function get_active() {
        $query = "SELECT active 
                        FROM users 
                        WHERE digest=:digest;";
        $parameters = array(new Parameter(':digest', $_SESSION['digest'], PDO::PARAM_STR));
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0]['active'] : null;
    }
    
    /**
     * Retrieves user's status with username
     */
    public function get_active_by_username($username) {
        $query = "SELECT active 
                        FROM users 
                        WHERE username=:username;";
        $parameters = array(new Parameter(':username', $username, PDO::PARAM_STR));
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0]['active'] : null;
    }

    /**
     * Retrieves user's information with digest
     */
    public function get_user() {
        $query = "SELECT username, 
                        active, 
                        name AS role 
                        FROM users 
                        INNER JOIN roles ON users.role = roles.id 
                        WHERE digest=:digest;";
        $parameters = array(new Parameter(':digest', $_SESSION['digest'], PDO::PARAM_STR));
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0] : null;
    }

    /**
     * Retrieves user's information with ID
     */
    public function get_user_by_id($id) {
        $query = "SELECT username, 
                        active, 
                        name AS role 
                        FROM users 
                        INNER JOIN roles ON users.role = roles.id 
                        WHERE users.id=:id;";
        $parameters = array(new Parameter(':id', $id, PDO::PARAM_INT));
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0] : null;
    }

    /**
     * Retrieves user's information with username
     */
    public function get_user_by_username($username) {
        $query = "SELECT username, 
                        active, 
                        name AS role 
                        FROM users 
                        INNER JOIN roles ON users.role = roles.id 
                        WHERE username=:username;";
        $parameters = array(new Parameter(':username', $username, PDO::PARAM_STR));
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0] : null;
    }

    /**
     * Retrieves all user's information except current user
     */
    public function get_data() {
        $query = "SELECT users.id, 
                        username, 
                        active, 
                        name AS role 
                        FROM users 
                        INNER JOIN roles ON users.role = roles.id 
                        WHERE digest<>:digest;";
        $parameters = array(new Parameter(':digest', $_SESSION['digest'], PDO::PARAM_STR));
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? $array : null;
    }

    /**
     * Get the whole table
     */
    public function get_table() {
        $query = "SELECT *
                        FROM users;";
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? $array : null;
    }
    
    /**
     * Insert a user
     */
    public function insert_one($user) {
        $user['salt'] = self::$_utils->random_str();
        $user['digest'] = self::$_authentication->hash_str("{$user['username']}{$user['salt']}{$user['password']}");
        
        $query = "INSERT INTO users (username, salt, digest, active, role) 
                        VALUES (:username, :salt, :digest, :active, :role);";

        $parameters = array(new Parameter(':username', $user['username'], PDO::PARAM_STR),
                new Parameter(':salt', $user['salt'], PDO::PARAM_STR),
                new Parameter(':digest', $user['digest'], PDO::PARAM_STR),
                new Parameter(':active', $user['active'], PDO::PARAM_BOOL),
                new Parameter(':role', $user['role'], PDO::PARAM_INT));

        self::$_database->query($query, $parameters);
    }
    
    /**
     * Insert users
     */
    public function insert_multiple($userArray) {
        foreach ($userArray as $user) {
            $this->insert_one($user);
        }
    }
    
    /**
     * Update the user's fingerprint
     */
    public function update_salt($salt) { 
        $query = "UPDATE users 
                        SET salt=:salt 
                        WHERE digest=:digest;";
        $parameters = array(new Parameter(':salt', $salt, PDO::PARAM_STR),
                            new Parameter(':digest', $_SESSION['digest'], PDO::PARAM_STR));

        self::$_database->query($query, $parameters);
    }
    
    /**
     * Update the user's fingerprint
     */
    public function update_digest($digest) {
        $query = "UPDATE users 
                        SET digest=:newDigest 
                        WHERE digest=:oldDigest;";
        $parameters = array(new Parameter(':newDigest', $digest, PDO::PARAM_STR),
                            new Parameter(':oldDigest', $_SESSION['digest'], PDO::PARAM_STR));

        self::$_database->query($query, $parameters);
    }
    
    /**
     * Update a user
     */
    public function update_one($user) {
        $setDigest = '';
        $parameters = array();
        
        if (is_string($user['digest'])) {
            $setDigest = "digest=:digest,";
            array_push($parameters, new Parameter(':digest', $user['digest'], PDO::PARAM_STR));
        }
        
        $query = "UPDATE users 
                        SET {$setDigest} 
                        active=:active, 
                        role=:role 
                        WHERE id=:id";
        array_push($parameters, new Parameter(':active', $user['active'], PDO::PARAM_BOOL),
                                new Parameter(':role', $user['role'], PDO::PARAM_INT),
                                new Parameter(':id', $user['id'], PDO::PARAM_INT));
        
        self::$_database->query($query, $parameters);
    }
    
    /**
     * Update users
     */
    public function update_multiple($userArray) {
        foreach ($userArray as $user) {
            $this->update_one($user);
        }
    }
    
    /**
     * Deletes a user
     */
    public function delete_one($id) {
        $this->$_mail->update_one($id);

        $query = "DELETE FROM users 
                            WHERE id=:id;";
        $parameters = array(new Parameter(':id', $id, PDO::PARAM_INT));

        self::$_database->query($query, $parameters);
    }
    
    /**
     * Deletes users
     */
    public function delete_multiple($idArray) {
        foreach ($idArray as $id) {
            $this->delete_one($id);
        }
    }
}
?>
