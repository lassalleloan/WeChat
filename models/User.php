<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to sen mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 **/

require_once('Authentication.php');
require_once('Database.php');
require_once('Mail.php');
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

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
            self::$_authentication = Authentication::getInstance();
            self::$_database = Database::getInstance();
            self::$_mail = Mail::getInstance();
            self::$_utils = Utils::getInstance();
        }
        
        return self::$_instance;
    }
    
    /**
     * Retrieves the user ID
     */
    public function getId() {
        return self::$_database->query("SELECT id
                                                FROM users
                                                WHERE digest='{$_SESSION['digest']}';");
    }
    
    /**
     * Retrieves a user's ID
     */
    public function getIdByUsername($username) {
        return self::$_database->query("SELECT id
                                                FROM users
                                                WHERE username='{$username}';");
    }
    
    /**
     * Retrieves the user name of the user
     */
    public function getUsername() {
        return self::$_database->query("SELECT username
                                                FROM users
                                                WHERE digest='{$_SESSION['digest']}';");
    }
    
    /**
     * Retrieves the credentials of the user
     */
    public function getCredentials() {
        return self::$_database->query("SELECT salt,
                                                digest
                                                FROM users
                                                WHERE digest='{$_SESSION['digest']}';");
    }
    
    /**
     * Retrieves a user's credentials
     */    
    public function getCredentialsByUsername($username) {
        return self::$_database->query("SELECT salt,
                                                digest
                                                FROM users
                                                WHERE username='{$username}';");
    }
    
    /**
     * Retrieves the role of a user
     */
    public function getRole() {
        return self::$_database->query("SELECT role
                                                FROM users
                                                WHERE digest='{$_SESSION['digest']}';");
    }
    
    /**
     * Retrieves the role of a user
     */
    public function getRoleByUsername($username) {
        return self::$_database->query("SELECT role
                                                FROM users
                                                WHERE username='{$username}';");
    }
    
    /**
     * Retrieves the status of a user
     */
    public function getActive() {
        return self::$_database->query("SELECT active
                                                FROM users
                                                WHERE digest='{$_SESSION['digest']}';");
    }
    
    /**
     * Retrieves the status of a user
     */
    public function getActiveByUsername($username) {
        return self::$_database->query("SELECT active
                                                FROM users
                                                WHERE username='{$username}';");
    }

    /**
     * Retrieves a user's information
     */
    public function getUser($id) {
        return self::$_database->query("SELECT username,
                                                active,
                                                name AS role
                                                FROM users 
                                                INNER JOIN roles ON users.role = roles.id
                                                WHERE users.id={$id};"); 
    }

    /**
     * Retrieves user information
     */
    public function getData() {
        return self::$_database->query("SELECT users.id,
                                                username,
                                                active,
                                                name AS role
                                                FROM users 
                                                INNER JOIN roles ON users.role = roles.id
                                                WHERE digest<>'{$_SESSION['digest']}';");  
    }

    /**
     * Get the whole table
     */
    public function getTable() {
        return self::$_database->query("SELECT * FROM users;");  
    }
    
    /**
     * Insert a user
     */
    public function insertOne($user) {
        $user['salt'] = self::$_utils->randomStr();
        $user['digest'] = self::$_authentication->hashStr("{$user['username']}{$user['salt']}{$user['password']}");
        self::$_database->query("INSERT INTO users (username, salt, digest, active, role) 
                                        VALUES ('{$user['username']}', '{$user['salt']}', '{$user['digest']}','{$user['active']}', '{$user['role']}');");
    }
    
    /**
     * Insert users
     */
    public function insertMultiple($userArray) {
        foreach ($userArray as $user) {
            self::insertOne($user);
        }
    }
    
    /**
     * Update the user's fingerprint
     */
    public function updateSalt($salt) {
        self::$_database->query("UPDATE users
                                        SET salt='{$salt}'
                                        WHERE digest='{$_SESSION['digest']}';");      
    }
    
    /**
     * Update the user's fingerprint
     */
    public function updateDigest($digest) {
        self::$_database->query("UPDATE users
                                        SET digest='{$digest}'
                                        WHERE digest='{$_SESSION['digest']}';");      
    }
    
    /**
     * Update a user
     */
    public function updateOne($user) {
        $setDigest = '';
        
        if (isset($user['digest'])) {
            $setDigest = "digest='{$user['digest']}',";
        }
        
        self::$_database->query("UPDATE users
                                        SET {$setDigest}
                                        active={$user['active']},
                                        role={$user['role']}
                                        WHERE id={$user['id']};");       
    }
    
    /**
     * Update users
     */
    public function updateMultiple($userArray) {
        foreach ($userArray as $user) {
            self::updateOne($user);
        }
    }
    
    /**
     * Deletes a user
     */
    public function deleteOne($id) {
        self::$_mail->updateOne($id);
        self::$_database->query("DELETE FROM users
                                        WHERE id={$id};");
    }
    
    /**
     * Deletes users
     */
    public function deleteMultiple($idArray) {
        foreach ($idArray as $id) {
            self::deleteOne($id);
        }
    }
}
?>
