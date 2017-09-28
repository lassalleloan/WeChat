<?php
require_once('Database.php');

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
        return Database::getInstance()->query('SELECT id
                                                FROM users
                                                WHERE digest="'.$_SESSION['digest'].'";');
    }
    
    public function getIdByUsername($username) {
        return Database::getInstance()->query('SELECT id
                                                FROM users
                                                WHERE username="'.$username.'";');
    }
    
    public function getUsername() {
        return Database::getInstance()->query('SELECT username
                                                FROM users
                                                WHERE digest="'.$_SESSION['digest'].'";');
    }
    
    public function getRole() {
        return Database::getInstance()->query('SELECT role
                                                FROM users
                                                WHERE digest="'.$_SESSION['digest'].'";');
    }
    
    public function getActive() {
        return Database::getInstance()->query('SELECT active
                                                FROM users
                                                WHERE digest="'.$_SESSION['digest'].'";');
    }
    
    public function getActiveByUsername($username) {
        return Database::getInstance()->query('SELECT active
                                                FROM users
                                                WHERE username="'.$username.'";');
    }
    
    public function getCredentials() {
        return Database::getInstance()->query('SELECT salt,
                                                digest
                                                FROM users
                                                WHERE digest="'.$_SESSION['digest'].'";');
    }
    
    public function getCredentialsByUsername($username) {
        return Database::getInstance()->query('SELECT salt,
                                                digest
                                                FROM users
                                                WHERE username="'.$username.'";');
    }

    public function getAll() {
        return Database::getInstance()->query('SELECT users.id,
                                                username,
                                                active,
                                                name AS role
                                                FROM users 
                                                INNER JOIN roles ON users.role = roles.id;');  
    }

    public function getAllUsers() {
        return Database::getInstance()->query('SELECT * FROM users;');  
    }
    
    public function insert($users) {
        foreach ($users as $u) {
            Database::getInstance()->query("INSERT INTO users (username, salt, digest, active, role) 
                    VALUES ('{$u['username']}', '{$u['salt']}', '{$u['digest']}','{$u['active']}', '{$u['role']}');");
        }
    }
    
    public function delete($id) {
        Database::getInstance()->query('UPDATE FROM users
                                        SET active = 0
                                        WHERE id='.$id.';');
    }
}
?>
