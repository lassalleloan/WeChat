<?php
class Mail {
    
    public function __construct($file = 'sqlite:./weChat.sqlite') {
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
}
?>
