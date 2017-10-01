<?php
/**************************************************
* STI - Project Web
* WeChat
* Description: web site to sen mails between users 
* Authors: Loan Lassalle, Wojciech Myszkorowski
**************************************************/

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
        $user_id = User::getInstance()->getId()->fetch()['id'];
        return Database::getInstance()->query("SELECT id
                                                FROM mails 
                                                INNER JOIN users ON mails.idReceiver = users.id
                                                WHERE date='{$date}'
                                                AND idReceiver={$user_id};");
    }

    public function getDate($id) {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        return Database::getInstance()->query("SELECT date
                                                FROM mails
                                                WHERE id={$id}
                                                AND idReceiver={$user_id};");
    }

    public function getTo($id) {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        return Database::getInstance()->query("SELECT username AS 'to'
                                                FROM mails
                                                INNER JOIN users ON mails.idSender = users.id
                                                WHERE mails.id={$id}
                                                AND idReceiver={$user_id};");
    }

    public function getSubject($id) {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        return Database::getInstance()->query("SELECT subject
                                                FROM mails
                                                WHERE id={$id}
                                                AND idReceiver={$user_id};");
    }

    public function getBody($id) {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        return Database::getInstance()->query("SELECT body
                                                FROM mails
                                                WHERE id={$id}
                                                AND idReceiver={$user_id};");
    }

    public function getById($id) {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        return Database::getInstance()->query("SELECT date,
                                                uSender.username AS 'from',
                                                uReceiver.username AS 'to',
                                                subject,
                                                body
                                                FROM mails 
                                                INNER JOIN users AS uSender ON mails.idSender = uSender.id
                                                INNER JOIN users AS uReceiver ON mails.idReceiver = uReceiver.id
                                                WHERE mails.id={$id}
                                                AND idReceiver={$user_id};");
    }

    public function getByDate($date) {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        return Database::getInstance()->query("SELECT mails.id,
                                                date,
                                                uSender.username AS 'from',
                                                uReceiver.username AS 'to',
                                                subject,
                                                body
                                                FROM mails 
                                                INNER JOIN users AS uSender ON mails.idSender = uSender.id
                                                INNER JOIN users AS uReceiver ON mails.idReceiver = uReceiver.id
                                                WHERE mails.date='{$date}'
                                                AND idReceiver={$user_id};");
    }

    public function getData() {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        return Database::getInstance()->query("SELECT mails.id,
                                                date, 
                                                uSender.username AS 'from',
                                                subject
                                                FROM mails 
                                                INNER JOIN users AS uSender ON mails.idSender = uSender.id
                                                WHERE idReceiver={$user_id};");
    }

    public function getTable() {
        return Database::getInstance()->query("SELECT * FROM mails;");
    }
    
    public function insertOne($mail) {
        Database::getInstance()->query("INSERT INTO mails (date, idSender, idReceiver, subject, body) 
                                        VALUES ('{$mail['date']}', '{$mail['idSender']}', '{$mail['idReceiver']}', '{$mail['subject']}', '{$mail['body']}');");
    }
    
    public function insertMultiple($mailArray) {
        foreach ($mailArray as $mail) {
            $this->insertOne($mail);
        }
    }
    
    public function deleteOne($id) {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        Database::getInstance()->query("DELETE FROM mails
                                        WHERE id={$id}
                                        AND idReceiver={$user_id};");
    }
    
    public function deleteMultiple($idArray) {
        foreach ($idArray as $id) {
            $this->deleteOne($id);
        }
    }
}
?>
