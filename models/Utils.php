<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 */

/**
 * Toolbox
 *
 * @author Matthieu Chatelan, Lassalle Loan, Wojciech Myszkorowski
 * @since 27.09.2017
 */
class Utils {
    
    private static $_instance;
    
    private function __construct() {
        
    }

    public static function get_instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }

    /**
     * Formats a date into a character string
     */
    public function date_str_format($date, $timeZone = 'UTC', $format = 'm.d.Y H:i') {
        return (new DateTime($date, new DateTimeZone($timeZone)))->format($format);
    }

    /**
     * Generates a random string
     */
    public function random_str($length = 255, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
        $str = '';
        $max = strlen($keyspace) - 1;
        
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[mt_rand(0, $max)];
        }
        
        return $str;
    }

    // TODO: Password Meter
    public function password_meter($password) {
        // https://code.tutsplus.com/tutorials/build-a-simple-password-strength-checker--net-7565
        // http://www.passwordmeter.com

        $MINIMUM_LENGTH = 8;
        $score = 0;
        $lenght;
        $num_upper = 0;

        if (isset($password) && is_string($password)) {
            $lenght = strlen($password);

            if ($lenght >= $MINIMUM_LENGTH) {
                $score += ($lenght * 4);

                $chars = str_split($password);
                foreach($chars as $char){
                    if (preg_match_all('/[A-Z]/', $char)) {
                        $num_upper++;
                    }
                }


                echo "nb upper: {$num_upper}";
            }
        }

        echo "password: {$password}";
        echo "score: {$score}";

        return $score;
    }

    public function redirect_if_is_not_correct_file_origin($files_origin) {
        $http_referer_file = substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '/') + 1);
        $ask_pos = strrpos($http_referer_file, '?');

        if ($ask_pos !== false) {
            $http_referer_file = substr($http_referer_file, 0, $ask_pos);
        }
        
        foreach ($files_origin as $file_origin) {
            if ($http_referer_file === $file_origin) {
                return;
            }
        }

        header('location:../home.php');
        exit();
    }
}
?>
