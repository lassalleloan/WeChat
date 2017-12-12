<?php
/**
 * STI - Project Web
 * WeChat
 * Description: web site to send mails between users 
 * Authors: Loan Lassalle, Wojciech Myszkorowski
 */

require_once('Database.php');
require_once('User.php');

/**
 * Gère les emails
 *
 * @author Lassalle Loan, Wojciech Myszkorowski
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
     * Récupère l'ID d'un email de l'utilisateur
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
     * Récupère la date d'un email de l'utilisateur
     */
    public function getDate($id) {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        return Database::getInstance()->query("SELECT date
                                                FROM mails
                                                WHERE id={$id}
                                                AND idReceiver={$user_id};");
    }

    /**
     * Récupère le destinataire d'un email de l'utilisateur
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
     * Récupère le sujet d'un email de l'utilisateur
     */
    public function getSubject($id) {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        return Database::getInstance()->query("SELECT subject
                                                FROM mails
                                                WHERE id={$id}
                                                AND idReceiver={$user_id};");
    }

    /**
     * Récupère le corps d'un email de l'utilisateur
     */
    public function getBody($id) {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        return Database::getInstance()->query("SELECT body
                                                FROM mails
                                                WHERE id={$id}
                                                AND idReceiver={$user_id};");
    }

    /**
     * Récupère un email de l'utilisateur
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
     * Récupère un email de l'utilisateur
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
     * Récupère un email de l'utilisateur
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
     * Récupère la table complète
     */
    public function getTable() {
        return Database::getInstance()->query("SELECT *
                                                FROM mails
                                                ORDER BY date;");
    }
    
    /**
     * Insère un email
     */
    public function insertOne($mail) {
        Database::getInstance()->query("INSERT INTO mails (date, idSender, idReceiver, subject, body) 
                                        VALUES ('{$mail['date']}', '{$mail['idSender']}', '{$mail['idReceiver']}', '{$mail['subject']}', '{$mail['body']}');");
    }
    
    /**
     * Insère des emails
     */
    public function insertMultiple($mailArray) {
        foreach ($mailArray as $mail) {
            $this->insertOne($mail);
        }
    }
    
    /**
     * Met à jour un emails
     */
    public function updateOne($id) {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        Database::getInstance()->query("UPDATE mails
                                        SET idSender=NULL
                                        WHERE idSender={$id}
                                        AND idReceiver={$user_id};");
    }
    
    /**
     * Met à jour des emails
     */
    public function updateMultiple($idArray) {
        foreach ($idArray as $id) {
            $this->updateOne($id);
        }
    }
    
    /**
     * Supprime un emails
     */
    public function deleteOne($id) {
        $user_id = User::getInstance()->getId()->fetch()['id'];
        Database::getInstance()->query("DELETE FROM mails
                                        WHERE id={$id}
                                        AND idReceiver={$user_id};");
    }
    
    /**
     * Supprime des emails
     */
    public function deleteMultiple($idArray) {
        foreach ($idArray as $id) {
            $this->deleteOne($id);
        }
    }
}
?>
