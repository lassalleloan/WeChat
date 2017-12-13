<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 */

require_once('Database.php');
require_once('User.php');

/**
 * Manage emails
 *
 * @author Matthieu Chatelan, Lassalle Loan, Wojciech Myszkorowski
 * @since 27.09.2017
 */
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

    /**
     * Retrieves an email ID of the user
     */
    public function getId($date) {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        return Database::getInstance()->query("SELECT id
                                                FROM mails 
                                                INNER JOIN users ON mails.idReceiver = users.id
                                                WHERE date='{$date}'
                                                AND idReceiver={$user_id};");
    }

    /**
     * Retrieves the date of an email from the user
     */
    public function getDate($id) {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        return Database::getInstance()->query("SELECT date
                                                FROM mails
                                                WHERE id={$id}
                                                AND idReceiver={$user_id};");
    }

    /**
     * Retrieves the recipient of an email from the user
     */
    public function getTo($id) {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        return Database::getInstance()->query("SELECT username AS 'to'
                                                FROM mails
                                                INNER JOIN users ON mails.idSender = users.id
                                                WHERE mails.id={$id}
                                                AND idReceiver={$user_id};");
    }

    /**
     * Retrieves the subject of an email from the user
     */
    public function getSubject($id) {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        return Database::getInstance()->query("SELECT subject
                                                FROM mails
                                                WHERE id={$id}
                                                AND idReceiver={$user_id};");
    }

    /**
     * Get the body of an email from the user
     */
    public function getBody($id) {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        return Database::getInstance()->query("SELECT body
                                                FROM mails
                                                WHERE id={$id}
                                                AND idReceiver={$user_id};");
    }

    /**
     * Retrieves an email from the user
     */
    public function getById($id) {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        return Database::getInstance()->query("SELECT date,
                                                uSender.username AS 'from',
                                                uReceiver.username AS 'to',
                                                subject,
                                                body
                                                FROM mails 
                                                LEFT JOIN users AS uSender ON mails.idSender = uSender.id
                                                INNER JOIN users AS uReceiver ON mails.idReceiver = uReceiver.id
                                                WHERE mails.id={$id}
                                                AND idReceiver={$user_id};");
    }

    /**
     * Retrieves an email from the user
     */
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

    /**
     * Retrieves an email from the user
     */
    public function getData() {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        return Database::getInstance()->query("SELECT mails.id,
                                                date, 
                                                uSender.username AS 'from',
                                                subject
                                                FROM mails 
                                                LEFT JOIN users AS uSender ON mails.idSender = uSender.id
                                                WHERE idReceiver={$user_id}
                                                ORDER BY date;");
    }

    /**
     * Get the whole table
     */
    public function getTable() {
        return Database::getInstance()->query("SELECT *
                                                FROM mails
                                                ORDER BY date;");
    }
    
    /**
     * Insert an email
     */
    public function insertOne($mail) {
        Database::getInstance()->query("INSERT INTO mails (date, idSender, idReceiver, subject, body) 
                                        VALUES ('{$mail['date']}', '{$mail['idSender']}', '{$mail['idReceiver']}', '{$mail['subject']}', '{$mail['body']}');");
    }
    
    /**
     * Inserts emails
     */
    public function insertMultiple($mailArray) {
        foreach ($mailArray as $mail) {
            $this->insertOne($mail);
        }
    }
    
    /**
     * Update an emails
     */
    public function updateOne($id) {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        Database::getInstance()->query("UPDATE mails
                                        SET idSender=NULL
                                        WHERE idSender={$id}
                                        AND idReceiver={$user_id};");
    }
    
    /**
     * Update emails
     */
    public function updateMultiple($idArray) {
        foreach ($idArray as $id) {
            $this->updateOne($id);
        }
    }
    
    /**
     * Deletes an email
     */
    public function deleteOne($id) {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        Database::getInstance()->query("DELETE FROM mails
                                        WHERE id={$id}
                                        AND idReceiver={$user_id};");
    }
    
    /**
     * Deletes emails
     */
    public function deleteMultiple($idArray) {
        foreach ($idArray as $id) {
            $this->deleteOne($id);
        }
    }
}
?>
