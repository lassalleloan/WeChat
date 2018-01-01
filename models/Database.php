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

    private static $_instance;
    private static $_pdo;
    
    private function __construct() {
        self::connection();
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
            self::$_pdo = new PDO($file);
            self::$_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            exit();
        }
    }

    /**
     * Closes the connection to the database
     */
    public function deconnection() {
        self::$_pdo = null;
    }

    /**
     * Get the result of a query
     */
    public function query($sql, $parameters) {
        if (!isset(self::$_pdo)) {
            self::connection();
        }

        $stmt = self::$_pdo->prepare($sql);
        
        foreach($parameters as $parameter) {
            $stmt->bindParam($parameter->get_name(), $parameter->get_value(), $parameter->get_pdo_type());
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }
}
?>
