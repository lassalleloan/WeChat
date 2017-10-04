<?php
/**************************************************
* STI - Project Web
* WeChat
* Description: web site to sen mails between users 
* Authors: Loan Lassalle, Wojciech Myszkorowski
**************************************************/

class Authentication {
    private static $_instance;
    
    private function __construct() {
    }

    public static function getInstance() {
        if (is_null(self::$_instance )) {
          self::$_instance = new self();
        }
        
        return self::$_instance;
    }

    public function getDigest($str) {
        return base64_encode(hash('sha512', $str, true));
    }

    public function isLogged() {
        session_start();
        
        return isset($_SESSION['logged']) && $_SESSION['logged'];
    }

    public function isNotLogged() {
        return !$this->isLogged();
    }

    public function goToLocation($cond, $location = 'index.php') {
        if ($cond) {
            header("location:{$location}");
        }
    }
}
?>