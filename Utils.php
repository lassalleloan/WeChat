<?php
/**************************************************
* STI - Project Web
* WeChat
* Description: web site to sen mails between users 
* Authors: Loan Lassalle, Wojciech Myszkorowski
**************************************************/

class Utils {
    private static $_instance;
    
    private function __construct() {
    }

    public static function getInstance() {
        if (is_null(self::$_instance )) {
          self::$_instance = new self();
        }
        
        return self::$_instance;
    }

    public function dateStrFormat($date, $timeZone = 'UTC', $format = 'm.d.Y H:i') {
        return (new DateTime($date, new DateTimeZone($timeZone)))->format($format);
    }
    
    function randomStr($length = 255, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
        $str = '';
        $max = strlen($keyspace) - 1;
        
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[mt_rand(0, $max)];
        }
        
        return $str;
    }
}
?>
