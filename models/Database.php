<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 */
 
/**
 * Manages the communication with the database
 *
 * @author Matthieu Chatelan, Lassalle Loan, Wojciech Myszkorowski
 * @since 27.09.2017
 */
class Database {
    private static $_instance;
    private $_database;
    
    private function __construct() {
        $this->connection();
    }

    public static function getInstance() {
        if (is_null(self::$_instance)) {
          self::$_instance = new self();
        }
        
        return self::$_instance;
    }

    /**
     * Retrieves the connection to the database
     */
    public function connection($file = 'sqlite:/var/www/databases/wechat.sqlite') {
        try {
            $this->_database = new PDO($file);
            $this->_database->setAttribute(PDO::ATTR_ERRMODE, 
                                    PDO::ERRMODE_EXCEPTION);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            exit();
        }
    }

    /**
     * Closes the connection to the database
     */
    public function deconnection() {
        $this->_database = null;
    }

    /**
     * Get the result of a query
     */
    public function query($sql) {
        if (!isset($this->_database)) {
            $this->connection();
        }
        
        if (substr($sql, 0, 6) === 'SELECT') {
            $results = $this->_database->query($sql);
        } else {
            $results = $this->_database->exec($sql);
        }
        
        return $results;
    }
}
?>
