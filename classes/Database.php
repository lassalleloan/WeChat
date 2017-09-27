<?php
class Database {
    private $_file;
    private $_conn;
    
    public function __construct($file = 'sqlite:..\databases\weChat.sqlite') {
        $this->setFile($file);
        $this->connection();
    }
    
    public function setFile($file) {
        if (!is_string($file)) {
            trigger_error('setFileDatabase: parameter is not a string', E_USER_WARNING);
            return;
        }
        
        $this->_file = $file;
    }
    
    public function connection() {
        try {
            $this->_conn = new PDO($this->_file);
            $this->_conn->setAttribute(PDO::ATTR_ERRMODE, 
                                    PDO::ERRMODE_EXCEPTION);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            exit();
        }
    }
    
    public function deconnection() {
        $this->_conn = null;
    }
    
    public function query($sql) {
        if (!isset($this->_conn)) {
            $this->connection();
        }
        
        if (substr($sql, 0, 6) === 'SELECT') {
            $results = $this->_conn->query($sql);
        } else {
            $results = $this->_conn->exec($sql);
        }
        
        return $results;
    }
}
?>