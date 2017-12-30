<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 */

require_once('Database.php');

/**
 * Manages roles
 *
 * @author Matthieu Chatelan, Lassalle Loan, Wojciech Myszkorowski
 * @since 27.09.2017
 */
class Role {

    private static $_instance;
    private static $_database;
    
    private function __construct() {

    }

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
            self::$_database = Database::getInstance();
        }
        
        return self::$_instance;
    }
    
    /**
     * Retrieves the ID of a role
     */
    public function getId($name) {
        return self::$_database->query("SELECT id
                                                FROM roles
                                                WHERE id={$name};");  
    }
    
    /**
     * Retrieves the name of a role
     */
    public function getName($id) {
        return self::$_database->query("SELECT name
                                                FROM roles
                                                WHERE id={$id};");  
    }
    
    /**
     * Retrieves the date of a role
     */
    public function getData($id) {
        return self::$_database->query("SELECT id,
                                                name
                                                FROM roles
                                                WHERE id={$id};");  
    }

    /**
     * Get the whole table
     */
    public function getTable() {
        return self::$_database->query("SELECT * FROM roles;");  
    }
    
    /**
     * Insert a role
     */
    public function insertOne($role) {
        self::$_database->query("INSERT INTO roles (name) 
                                        VALUES ('{$role['name']}');");
    }
    
    /**
     * Insert roles
     */
    public function insertMultiple($roleArray) {
        foreach ($roleArray as $role) {
            self::insertOne($role);
        }
    }
    
    /**
     * Update a role
     */
    public function updateOne($role) {        
        self::$_database->query("UPDATE roles
                                        SET name={$role['name']}
                                        WHERE id={$role['id']};");       
    }
    
    /**
     * Update roles
     */
    public function updateMultiple($roleArray) {
        foreach ($roleArray as $role) {
            self::updateOne(role);
        }
    }
}
?>
