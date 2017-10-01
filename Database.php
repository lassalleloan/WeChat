<?php
/**************************************************
* STI - Project Web
* WeChat
* Description: web site to sen mails between users 
* Authors: Loan Lassalle, Wojciech Myszkorowski
**************************************************/

class Database {
    private static $_instance;
    private $_database;
    
    private function __construct() {
        $this->connection();
    }

    public static function getInstance() {
        if (is_null(self::$_instance )) {
          self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    public function connection($file = 'sqlite:./weChat.sqlite') {
        try {
            $this->_database = new PDO($file);
            $this->_database->setAttribute(PDO::ATTR_ERRMODE, 
                                    PDO::ERRMODE_EXCEPTION);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            exit();
        }
    }
    
    public function deconnection() {
        $this->_database = null;
    }
    
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
