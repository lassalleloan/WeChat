<?php
require_once('Database.php');

class Mail {
    private static $instance;
    
    private function __construct() {
    }

    public static function getInstance() {
        if (is_null(self::$instance )) {
          self::$instance = new self();
        }
        
        return self::$instance;
    }

    public function getAllMail() {
        return Database::getInstance()->query('SELECT date, 
                                                username,
                                                subject
                                                FROM mails 
                                                INNER JOIN users ON mails.idSender = users.id 
                                                WHERE idReceiver=(SELECT id FROM users WHERE digest="'.$_SESSION['digest'].'");');
    }
}
?>
