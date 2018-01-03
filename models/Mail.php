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
    private $_database;
    private $_user;
    
    private function __construct() {
        $this->_database = Database::get_instance();
        $this->_user = User::get_instance();
    }

    public static function get_instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }

    /**
     * Retrieves user's email ID
     */
    public function get_id($date) {
        $user_id = $this->_user->get_id();
        $query = "SELECT id 
                        FROM mails 
                        INNER JOIN users ON mails.idReceiver = users.id 
                        WHERE date=:date 
                        AND idReceiver=:user_id;";
        $parameters = array(new Parameter(':date', $date, PDO::PARAM_STR),
                            new Parameter(':user_id', $user_id, PDO::PARAM_INT));
        $array = $this->_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0]['id'] : null;
    }

    /**
     * Retrieves the date of user's email
     */
    public function get_date($id) {
        $user_id = $this->_user->get_id();
        $query = "SELECT date 
                        FROM mails 
                        WHERE id=:id 
                        AND idReceiver=:user_id;";
        $parameters = array(new Parameter(':id', $id, PDO::PARAM_INT),
                            new Parameter(':user_id', $user_id, PDO::PARAM_INT));
        $array = $this->_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0]['date'] : null;
    }

    /**
     * Retrieves the recipient of user's email
     */
    public function get_to($id) {
        $user_id = $this->_user->get_id();
        $query = "SELECT username AS 'to' 
                        FROM mails 
                        INNER JOIN users ON mails.idSender = users.id 
                        WHERE mails.id=:id 
                        AND idReceiver=:user_id;";
        $parameters = array(new Parameter(':id', $id, PDO::PARAM_INT),
                            new Parameter(':user_id', $user_id, PDO::PARAM_INT));
        $array = $this->_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0]['to'] : null;
    }

    /**
     * Retrieves the subject of user's email
     */
    public function get_subject($id) {
        $user_id = $this->_user->get_id();
        $query = "SELECT subject 
                        FROM mails 
                        WHERE id=:id 
                        AND idReceiver=:user_id;";
        $parameters = array(new Parameter(':id', $id, PDO::PARAM_INT),
                            new Parameter(':user_id', $user_id, PDO::PARAM_INT));
        $array = $this->_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0]['subject'] : null;
    }

    /**
     * Get the body of user's email
     */
    public function get_body($id) {
        $user_id = $this->_user->get_id();
        $query = "SELECT body 
                        FROM mails 
                        WHERE id=:id 
                        AND idReceiver=:user_id;";
        $parameters = array(new Parameter(':id', $id, PDO::PARAM_INT),
                            new Parameter(':user_id', $user_id, PDO::PARAM_INT));
        $array = $this->_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0]['body'] : null;
    }

    /**
     * Retrieves user's email
     */
    public function get_by_id($id) {
        $user_id = $this->_user->get_id();
        $query = "SELECT date, 
                        uSender.username AS 'from', 
                        uReceiver.username AS 'to', 
                        subject, 
                        body 
                        FROM mails 
                        LEFT JOIN users AS uSender ON mails.idSender = uSender.id 
                        INNER JOIN users AS uReceiver ON mails.idReceiver = uReceiver.id 
                        WHERE mails.id=:id 
                        AND idReceiver=:user_id;";
        $parameters = array(new Parameter(':id', $id, PDO::PARAM_INT),
                            new Parameter(':user_id', $user_id, PDO::PARAM_INT));
        $array = $this->_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0] : null;
    }

    /**
     * Retrieves user's email
     */
    public function get_by_date($date) {
        $user_id = $this->_user->get_id();
        $query = "SELECT mails.id, 
                        date, 
                        uSender.username AS 'from', 
                        uReceiver.username AS 'to', 
                        subject, 
                        body 
                        FROM mails 
                        INNER JOIN users AS uSender ON mails.idSender = uSender.id 
                        INNER JOIN users AS uReceiver ON mails.idReceiver = uReceiver.id 
                        WHERE mails.date=:date 
                        AND idReceiver=:user_id;";
        $parameters = array(new Parameter(':date', $date, PDO::PARAM_STR),
                            new Parameter(':user_id', $user_id, PDO::PARAM_INT));
        $array = $this->_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0] : null;
    }

    /**
     * Check if the user is associate to email
     */
    public function is_associate_to_user($id) {
        return !$this->is_not_associate_to_user($id);
    }

    /**
     * Check if the user is not associate to email
     */
    public function is_not_associate_to_user($id) {
        return !$this->get_by_id($id);
    }

    /**
     * Redirect the user if is not associated to email
     */
    public function redirect_if_is_not_associate_to_user($id) {
        if ($this->is_not_associate_to_user($id)) {
            $this->_database->deconnection();
            header('location:home.php');
            exit;
        }
    }

    /**
     * Retrieves all user's email information
     */
    public function get_data() {
        $user_id = $this->_user->get_id();
        $query = "SELECT mails.id, 
                        date, 
                        uSender.username AS 'from', 
                        subject 
                        FROM mails 
                        LEFT JOIN users AS uSender ON mails.idSender = uSender.id 
                        WHERE idReceiver=:user_id 
                        ORDER BY date;";
        $parameters = array(new Parameter(':user_id', $user_id, PDO::PARAM_INT));
        $array = $this->_database->query($query, $parameters);

        return count($array) >= 1 ? $array : null;
    }

    /**
     * Get the whole table
     */
    public function get_table() {
        $query = "SELECT * 
                        FROM mails 
                        ORDER BY date;";
        $array = $this->_database->query($query, $parameters);

        return count($array) >= 1 ? $array : null;
    }
    
    /**
     * Insert an email
     */
    public function insert_one($mail) {
        $mail['idSender'] = $this->_user->get_id_by_username($mail['sender']);
        $mail['idReceiver'] = $this->_user->get_id_by_username($mail['receiver']);

        if (isset($mail['idSender']) && isset($mail['idReceiver'])) {
            $query = "INSERT INTO mails (date, idSender, idReceiver, subject, body)  
                                VALUES (:date, :idSender, :idReceiver, :subject, :body);";
            $parameters = array(new Parameter(':date', $mail['date'], PDO::PARAM_STR),
                                new Parameter(':idSender', $mail['idSender'], PDO::PARAM_INT),
                                new Parameter(':idReceiver', $mail['idReceiver'], PDO::PARAM_INT),
                                new Parameter(':subject', $mail['subject'], PDO::PARAM_STR),
                                new Parameter(':body', $mail['body'], PDO::PARAM_STR));

            $this->_database->query($query, $parameters);
        }
    }
    
    /**
     * Inserts emails
     */
    public function insert_multiple($mailArray) {
        foreach ($mailArray as $mail) {
            $this->insert_one($mail);
        }
    }
    
    /**
     * Update an emails
     */
    public function update_one($id) {
        $user_id = $this->_user->get_id();
        $query = "UPDATE mails 
                        SET idSender=NULL 
                        WHERE idSender=:id 
                        AND idReceiver=:user_id;";
        $parameters = array(new Parameter(':id', $id, PDO::PARAM_INT),
                            new Parameter(':user_id', $user_id, PDO::PARAM_INT));

        $this->_database->query($query, $parameters);  
    }
    
    /**
     * Update emails
     */
    public function update_multiple($idArray) {
        foreach ($idArray as $id) {
            $this->update_one($id);
        }
    }
    
    /**
     * Deletes an email
     */
    public function delete_one($id) {
        $user_id = $this->_user->get_id();
        $query = "DELETE FROM mails 
                        WHERE id=:id
                        AND idReceiver=:user_id;";
        $parameters = array(new Parameter(':id', $id, PDO::PARAM_INT),
                            new Parameter(':user_id', $user_id, PDO::PARAM_INT));

        $this->_database->query($query, $parameters);
    }
    
    /**
     * Deletes emails
     */
    public function delete_multiple($idArray) {
        foreach ($idArray as $id) {
            $this->delete_one($id);
        }
    }
}
?>
