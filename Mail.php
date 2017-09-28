<?php
require_once('Database.php');
require_once('User.php');

class Mail {
    private static $_instance;
    
    private function __construct() {
    }

    public static function getInstance() {
        if (is_null(self::$_instance )) {
          self::$_instance = new self();
        }
        
        return self::$_instance;
    }

    public function getId($date) {
        $row = User::getInstance()->getId()->fetch();
        return Database::getInstance()->query('SELECT id
                                                FROM mails 
                                                INNER JOIN users ON mails.idReceiver = users.id
                                                WHERE date="'.$date.'"
                                                AND idReceiver='.$row['id'].';');
    }

    public function getDate($id) {
        $row = User::getInstance()->getId()->fetch();
        return Database::getInstance()->query('SELECT date
                                                FROM mails
                                                WHERE id="'.$id.'"
                                                AND idReceiver='.$row['id'].';');
    }

    public function getById($id) {
        $row = User::getInstance()->getId()->fetch();
        return Database::getInstance()->query('SELECT date,
                                                uSender.username AS "from",
                                                uReceiver.username AS "to",
                                                subject,
                                                body
                                                FROM mails 
                                                INNER JOIN users AS uSender ON mails.idSender = uSender.id
                                                INNER JOIN users AS uReceiver ON mails.idReceiver = uReceiver.id
                                                WHERE mails.id="'.$id.'"
                                                AND idReceiver='.$row['id'].';');
    }

    public function getByDate($date) {
        $row = User::getInstance()->getId()->fetch();
        return Database::getInstance()->query('SELECT date,
                                                uSender.username AS "from",
                                                uReceiver.username AS "to",
                                                subject,
                                                body
                                                FROM mails 
                                                INNER JOIN users AS uSender ON mails.idSender = uSender.id
                                                INNER JOIN users AS uReceiver ON mails.idReceiver = uReceiver.id
                                                WHERE mails.date="'.$date.'"
                                                AND idReceiver='.$row['id'].';');
    }

    public function getAll() {
        $row = User::getInstance()->getId()->fetch();
        return Database::getInstance()->query('SELECT mails.id,
                                                date, 
                                                uSender.username AS "from",
                                                subject
                                                FROM mails 
                                                INNER JOIN users AS uSender ON mails.idSender = uSender.id
                                                WHERE idReceiver='.$row['id'].';');
    }

    public function getAllMails() {
        return Database::getInstance()->query('SELECT * FROM mails;');
    }
    
    public function insert($mail) {
        foreach ($mail as $m) {
            Database::getInstance()->query("INSERT INTO mails (date, idSender, idReceiver, subject, body) 
                    VALUES ('{$m['date']}', '{$m['idSender']}', '{$m['idReceiver']}', '{$m['subject']}', '{$m['body']}');");
        }
    }
    
    public function delete($id) {
        $row = User::getInstance()->getId()->fetch();
        Database::getInstance()->query('DELETE FROM mails
                                        WHERE id='.$id.'
                                        AND idReceiver='.$row['id'].';');
    }
}
?>
