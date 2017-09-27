<?php
require_once('Database.php');

class User {
    private static $instance;
    
    private function __construct() {
    }

    public static function getInstance() {
        if (is_null(self::$instance )) {
          self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    public function getFields($fields) {
        return Database::getInstance()->query('SELECT '.$fields.' FROM users
                                                WHERE digest="'.$_SESSION['digest'].'";');
    }

    public function getAllRows() {
        return Database::getInstance()->query('SELECT username,
                                                active,
                                                role
                                                FROM users 
                                                INNER JOIN roles ON users.id = roles.id;');  
    }
}
?>
