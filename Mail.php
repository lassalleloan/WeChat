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
        return Database::getInstance()->query('SELECT date AS Date, 
                                                username AS Username, 
                                                subject AS Subject
                                                FROM mails 
                                                INNER JOIN users ON mails.idSender = users.id 
                                                WHERE idReceiver=(SELECT id FROM users WHERE digest="'.$_SESSION['digest'].'");');
    }
}
?>
