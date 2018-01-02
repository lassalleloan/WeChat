<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 */
 
/**
 * Manages the communication with the database
 *
 * @author Matthieu Chatelan, Lassalle Loan, Wojciech Myszkorowski
 * @since 27.09.2017
 */
class Database {

    const PHP_INT_MIN = 1;
    const PHP_INT_MAX = 2147483647;

    const PHP_STR_MIN = 3;
    const PHP_STR_MAX = 255;
    
    const PHP_TEXT_MIN = 3;
    const PHP_TEXT_MAX = 1024;
    
    const PHP_DATE_LEN = 23;
    
    const USERNAME_MIN = 3;
    const USERNAME_MAX = 50;
    
    const PASSWORD_MIN = 8;
    const PASSWORD_MAX = 50;
    
    const DIGEST_LEN = 88;

    private static $_instance;
    private $_pdo;
    
    private function __construct() {
        $this->connection();
    }

    public static function get_instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }

    /**
     * Retrieves the connection to the database
     */
    public function connection($file = 'sqlite:/var/www/databases/wechat.sqlite') {
        try {
            $this->_pdo = new PDO($file);
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Closes the connection to the database
     */
    public function deconnection() {
        $this->_pdo = null;
    }

    /**
     * Get the result of a query
     */
    public function query($sql, $parameters) {
        if (!isset($this->_pdo)) {
            $this->connection();
        }

        $stmt = $this->_pdo->prepare($sql);
        
        if (isset($parameters)) {
            foreach($parameters as $parameter) {
                $stmt->bindParam($parameter->get_name(), $parameter->get_value(), $parameter->get_pdo_type());
            }
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }
}
?>
