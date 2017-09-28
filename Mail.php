<?php
require_once('Database.php');
require_once('User.php');

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

    public function getMail($date) {
        $row = User::getInstance()->getFields('id')->fetch();
        return Database::getInstance()->query('SELECT date,
                                                u1.username AS "from",
                                                u2.username AS "to",
                                                subject,
                                                body
                                                FROM mails 
                                                INNER JOIN users AS u1 ON mails.idSender = u1.id
                                                INNER JOIN users AS u2 ON mails.idReceiver = u2.id
                                                WHERE date="'.$date.'" AND idReceiver='.$row['id'].';');
    }

    public function getAllMail() {
        $row = User::getInstance()->getFields('id')->fetch();
        return Database::getInstance()->query('SELECT date, 
                                                username AS "from",
                                                subject
                                                FROM mails 
                                                INNER JOIN users ON mails.idSender = users.id
                                                WHERE idReceiver='.$row['id'].';');
    }

    public function sendMail($mail) {
        foreach ($mail as $m) {
            Database::getInstance()->query("INSERT INTO mails (date, idSender, idReceiver, subject, body) 
                    VALUES ('{$m['date']}', '{$m['idSender']}', '{$m['idReceiver']}', '{$m['subject']}', '{$m['body']}');");
        }
    }
}
?>
