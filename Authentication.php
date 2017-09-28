<?php
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

    public function hash($str, $hashFunction = 'sha512') {
        return base64_encode(hash($hashFunction, $str, true));
    }

    public function isLogged() {
        session_start();
        
        return isset($_SESSION['logged']) && $_SESSION['logged'];
    }

    public function toIndex() {        
        if (!$this->isLogged()) {
            header('location:index.php');
        }
    }

    public function toHome() {        
        if ($this->isLogged()) {
            header('location:home.php');
        }
    }
}
?>