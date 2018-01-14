<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 */

require_once('Database.php');

/**
 * Manage blacklist of ip address
 *
 * @author Matthieu Chatelan, Lassalle Loan, Wojciech Myszkorowski
 * @since 27.09.2017
 */
class Blacklist {
    
    const ATTEMPTS_MAX = 4;

    private static $_instance;
    private static $_database;
    
    private function __construct() {

    }

    public static function get_instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
            
            self::$_database = Database::get_instance();
        }
        
        return self::$_instance;
    }

    /**
     * Retrieves ip address' ID
     */
    public function get_id() {
        $query = "SELECT id 
                        FROM blacklist 
                        WHERE ip=:ip;";
        $parameters = array(new Parameter(':ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR));
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? (int)$array[0]['id'] : null;
    }

    /**
     * Retrieves blacklisted
     */
    public function get_blacklist() {
        $blacklist_id = $this->get_id();
        $query = "SELECT date,
                    attempt,
                    ip
                    FROM blacklist
                    WHERE id=:id;";
        $parameters = array(new Parameter(':id', $blacklist_id, PDO::PARAM_STR));
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? $array[0] : null;
    }

    /**
     * Retrieves blacklisted attempt
     */
    public function get_attempt() {
        $blacklist_id = $this->get_id();
        $query = "SELECT attempt
                    FROM blacklist
                    WHERE id=:id;";
        $parameters = array(new Parameter(':id', $blacklist_id, PDO::PARAM_STR));
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? (int)$array[0]['attempt'] : null;
    }

    /**
     * Get the whole table
     */
    public function get_table() {
        $query = "SELECT * 
                        FROM blacklist 
                        ORDER BY date;";
        $array = self::$_database->query($query, $parameters);

        return count($array) >= 1 ? $array : null;
    }
    
    /**
     * Set a blacklist
     */
    public function set() {
        $blacklist = [
            'date' => substr(date('Y-m-d\TH:i:s.u'), 0, Database::PHP_DATE_LEN),
            'attempt' => 1,
            'ip' => $_SERVER['REMOTE_ADDR']
        ];
        $this->insert_one($blacklist);
    }
    
    /**
     * Insert a blacklist
     */
    public function insert_one($blacklist) {
        $query = "INSERT INTO blacklist (date, attempt, ip) 
                            VALUES (:date, :attempt, :ip);";
        $parameters = array(new Parameter(':date', $blacklist['date'], PDO::PARAM_STR),
                            new Parameter(':attempt', $blacklist['attempt'], PDO::PARAM_INT),
                            new Parameter(':ip', $blacklist['ip'], PDO::PARAM_STR));

        self::$_database->query($query, $parameters);
    }
    
    /**
     * Inserts blacklists
     */
    public function insert_multiple($blacklist_array) {
        foreach ($blacklist_array as $blacklist) {
            $this->insert_one($blacklist);
        }
    }
    
    /**
     * Increment attempt
     */
    public function increment_attempt() {
        $blacklist['date'] = substr(date('Y-m-d\TH:i:s.u'), 0, Database::PHP_DATE_LEN);
        $blacklist['attempt'] = $this->get_attempt() + 1;
        $this->update_one($blacklist);  
    }
    
    /**
     * Maximum attempt
     */
    public function max_attempt() {
        $blacklist['date'] = substr(date('Y-m-d\TH:i:s.u'), 0, Database::PHP_DATE_LEN);
        $blacklist['attempt'] = 6;
        $this->update_one($blacklist);  
    }
    
    /**
     * Reset a blacklist
     */
    public function reset() {
        $blacklist['date'] = substr(date('Y-m-d\TH:i:s.u'), 0, Database::PHP_DATE_LEN);
        $blacklist['attempt'] = 0;
        $this->update_one($blacklist);  
    }
    
    /**
     * Update a blacklist
     */
    public function update_one($blacklist) {
        $blacklist_id = $this->get_id();
        $query = "UPDATE blacklist 
                        SET date=:date, 
                        attempt=:attempt
                        WHERE id=:id";
        $parameters = array(new Parameter(':date', $blacklist['date'], PDO::PARAM_STR),
                            new Parameter(':attempt', $blacklist['attempt'], PDO::PARAM_INT),
                            new Parameter(':id', $blacklist_id, PDO::PARAM_STR));

        self::$_database->query($query, $parameters);  
    }
    
    /**
     * Update blacklists
     */
    public function update_multiple($blacklist_array) {
        foreach ($blacklist_array as $blacklist) {
            $this->update_one($blacklist);
        }
    }
    
    /**
     * Deletes a blacklist
     */
    public function delete_one($blacklist) {
        $blacklist_id = $this->get_id();
        $query = "DELETE FROM blacklist 
                        WHERE id=:id;";
        $parameters = array(new Parameter(':id', $blacklist_id, PDO::PARAM_INT));

        self::$_database->query($query, $parameters);
    }
    
    /**
     * Deletes blacklists
     */
    public function delete_multiple($blacklist_array) {
        foreach ($blacklist_array as $blacklist) {
            $this->delete_one($blacklist);
        }
    }
}
?>
