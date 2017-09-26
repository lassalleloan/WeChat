<?php
class Authentication {

    public static function check() {
        session_start();
        
        if (!isset($_SESSION['logged'])) {
            header('location:index.php');
            exit();
        }
    }
}
?>