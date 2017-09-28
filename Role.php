<?php
require_once('Database.php');

class Role {
    private static $_instance;
    
    private function __construct() {
    }

    public static function getInstance() {
        if (is_null(self::$_instance )) {
          self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    public function getId($name) {
        return Database::getInstance()->query('SELECT id
                                                FROM roles
                                                WHERE name="'.$name.'";');
    }
    
    public function getName($id) {
        return Database::getInstance()->query('SELECT name
                                                FROM roles
                                                WHERE id='.$id.';');
    }

    public function getAll() {
        return Database::getInstance()->query('SELECT *
                                                FROM roles;');  
    }
    
    public function insert($roles) {
        foreach ($roles as $r) {
            Database::getInstance()->query("INSERT INTO roles (name) 
                    VALUES ('{$r['name']}');");
        }
    }
}
?>
