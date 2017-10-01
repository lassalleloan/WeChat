<?php
/**************************************************
* STI - Project Web
* WeChat
* Description: web site to sen mails between users 
* Authors: Loan Lassalle, Wojciech Myszkorowski
**************************************************/

require_once('Authentication.php');
require_once('Database.php');
require_once('Utils.php');

class User {
    private static $_instance;
    
    private function __construct() {
    }

    public static function getInstance() {
        if (is_null(self::$_instance )) {
          self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    public function getId() {
        return Database::getInstance()->query("SELECT id
                                                FROM users
                                                WHERE digest='{$_SESSION['digest']}';");
    }
    
    public function getIdByUsername($username) {
        return Database::getInstance()->query("SELECT id
                                                FROM users
                                                WHERE username='{$username}';");
    }
    
    public function getUsername() {
        return Database::getInstance()->query("SELECT username
                                                FROM users
                                                WHERE digest='{$_SESSION['digest']}';");
    }
    
    public function getCredentials() {
        return Database::getInstance()->query("SELECT salt,
                                                digest
                                                FROM users
                                                WHERE digest='{$_SESSION['digest']}';");
    }
    
    public function getCredentialsByUsername($username) {
        return Database::getInstance()->query("SELECT salt,
                                                digest
                                                FROM users
                                                WHERE username='{$username}';");
    }
    
    public function getRole() {
        return Database::getInstance()->query("SELECT role
                                                FROM users
                                                WHERE digest='{$_SESSION['digest']}';");
    }
    
    public function getRoleByUsername($username) {
        return Database::getInstance()->query("SELECT role
                                                FROM users
                                                WHERE username='{$username}';");
    }
    
    public function getActive() {
        return Database::getInstance()->query("SELECT active
                                                FROM users
                                                WHERE digest='{$_SESSION['digest']}';");
    }
    
    public function getActiveByUsername($username) {
        return Database::getInstance()->query("SELECT active
                                                FROM users
                                                WHERE username='{$username}';");
    }

    public function getUser($id) {
        return Database::getInstance()->query("SELECT username,
                                                active,
                                                name AS role
                                                FROM users 
                                                INNER JOIN roles ON users.role = roles.id
                                                WHERE users.id={$id};"); 
    }

    public function getData() {
        return Database::getInstance()->query("SELECT users.id,
                                                username,
                                                active,
                                                name AS role
                                                FROM users 
                                                INNER JOIN roles ON users.role = roles.id
                                                WHERE digest<>'{$_SESSION['digest']}';");  
    }

    public function getTable() {
        return Database::getInstance()->query("SELECT * FROM users;");  
    }
    
    public function insertOne($user) {
        $user['salt'] = Utils::getInstance()->randomStr();
        $user['digest'] = Authentication::getInstance()->getDigest("{$user['username']}{$user['salt']}{$user['password']}");
        Database::getInstance()->query("INSERT INTO users (username, salt, digest, active, role) 
                                        VALUES ('{$user['username']}', '{$user['salt']}', '{$user['digest']}','{$user['active']}', '{$user['role']}');");
    }
    
    public function insertMultiple($userArray) {
        foreach ($userArray as $user) {
            $this->insertOne($user);
        }
    }
    
    public function updateDigest($digest) {
        Database::getInstance()->query("UPDATE users
                                        SET digest='{$digest}'
                                        WHERE digest='{$_SESSION['digest']}';");      
    }
    
    public function updateOne($user) {
        $setDigest = '';
        
        if (isset($user['digest'])) {
            $setDigest = "digest='{$user['digest']}',";
        }
        
        Database::getInstance()->query("UPDATE users
                                        SET {$setDigest}
                                        active={$user['active']},
                                        role={$user['role']}
                                        WHERE id={$user['id']};");       
    }
    
    public function updateMultiple($userArray) {
        foreach ($userArray as $user) {
            $this->updateOne($user);
        }
    }
    
    public function deleteOne($id) {
        Database::getInstance()->query("UPDATE users
                                        SET active=0
                                        WHERE id={$id};");
    }
    
    public function deleteMultiple($idArray) {
        foreach ($idArray as $id) {
            $this->deleteOne($id);
        }
    }
}
?>
