<?php
class Authentication {
    private static $instance;
    
    private function __construct() {
    }

    public static function getInstance() {
        if (is_null(self::$instance )) {
          self::$instance = new self();
        }
        
        return self::$instance;
    }

    public function check() {
        session_start();
        
        if (!isset($_SESSION['logged'])) {
            header('location:index.php');
            exit();
        }
    }
}
?>