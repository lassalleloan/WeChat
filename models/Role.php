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

    public static function get_instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
            self::$_database = Database::get_instance();
        }
        
        return self::$_instance;
    }
    
    /**
     * Retrieves role's ID
     */
    public function get_id($name) {
        $query = "SELECT id 
                        FROM roles 
                        WHERE id=:name;";
        $parameters = array(new Parameter(':name', $name, PDO::PARAM_STR));
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0]['id'] : null;
    }
    
    /**
     * Retrieves role's name
     */
    public function get_name($id) {
        $query = "SELECT name 
                        FROM roles 
                        WHERE id=:id;";
        $parameters = array(new Parameter(':id', $id, PDO::PARAM_STR));
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0]['name'] : null;
    }
    
    /**
     * Retrieves all role's information
     */
    public function get_data($id) {
        $query = "SELECT id,
                        name 
                        FROM roles 
                        WHERE id=:id;";
        $parameters = array(new Parameter(':id', $id, PDO::PARAM_STR));
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0] : null;
    }

    /**
     * Get the whole table
     */
    public function get_table() {
        $query = "SELECT * 
                        FROM roles;";
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? $array : null;
    }
    
    /**
     * Insert a role
     */
    public function insert_one($role) {
        $query = "INSERT INTO roles (name) 
                            VALUES (:name);";
        $parameters = array(new Parameter(':name', $role['name'], PDO::PARAM_STR));

        self::$_database->query($query, $parameters);
    }
    
    /**
     * Insert roles
     */
    public function insert_multiple($roleArray) {
        foreach ($roleArray as $role) {
            $this->insert_one($role);
        }
    }
    
    /**
     * Update a role
     */
    public function update_one($role) {
        $query = "UPDATE roles 
                        SET name=:name  
                        WHERE id=:id;";
        $parameters = array(new Parameter(':name', $role['name'], PDO::PARAM_STR),
                            new Parameter(':id', $role['id'], PDO::PARAM_STR));

        self::$_database->query($query, $parameters);      
    }
    
    /**
     * Update roles
     */
    public function update_multiple($roleArray) {
        foreach ($roleArray as $role) {
            $this->update_one(role);
        }
    }
}
?>
