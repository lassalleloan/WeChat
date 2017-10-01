<?php
/**************************************************
* STI - Project Web
* WeChat
* Description: web site to sen mails between users 
* Authors: Loan Lassalle, Wojciech Myszkorowski
**************************************************/

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
        return Database::getInstance()->query("SELECT id
                                                FROM roles
                                                WHERE id={$name};");  
    }
    
    public function getName($id) {
        return Database::getInstance()->query("SELECT name
                                                FROM roles
                                                WHERE id={$id};");  
    }

    public function getData() {
        return Database::getInstance()->query("SELECT id,
                                                name
                                                FROM roles
                                                WHERE id={$id};");  
    }

    public function getTable() {
        return Database::getInstance()->query("SELECT * FROM roles;");  
    }
    
    public function insertOne($role) {
        Database::getInstance()->query("INSERT INTO roles (name) 
                                        VALUES ('{$role['name']}');");
    }
    
    public function insertMultiple($roleArray) {
        foreach ($roleArray as $role) {
            $this->insertOne($role);
        }
    }
    
    public function updateOne($role) {        
        Database::getInstance()->query("UPDATE roles
                                        SET name={$role['name']}
                                        WHERE id={$role['id']};");       
    }
    
    public function updateMultiple($roleArray) {
        foreach ($roleArray as $role) {
            $this->updateOne(role);
        }
    }
}
?>
