<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 */

require_once('Database.php');

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
        // rootroot1+aA

        $lenght;
        $num_upper = 0;
        $num_lower = 0;
        $num_number = 0;
        $num_symbol = 0;
        $score = 0;

        if (isset($password) && is_string($password)) {
            $lenght = strlen($password);

            if ($lenght >= Database::PASSWORD_MIN) {
                $score += ($lenght * 4);

                $chars = str_split($password);
                foreach($chars as $char){
                    if (preg_match_all('/[A-Z]/', $char)) {
                        $num_upper++;
                    }

                    if (preg_match_all('/[a-z]/', $char)) {
                        $num_lower++;
                    }

                    if (preg_match_all('/[0-9]/', $char)) {
                        $num_number++;
                    }

                    if (preg_match_all('/[+"*#%&\/()=?\'`^!$£€,;.:\-_<>@|~{}[\]§°±“≠¿´‘¶¢…«»µ∫√©≈¥≤≥ß∂ƒªº∆¬πø°†®∑]/', $char)) {
                        $num_symbol++;
                    }
                }

                $score += ($lenght - $num_upper) * 2 + ($lenght - $num_lower) * 2 + $num_number * 4 + $num_symbol * 6;

                if (!preg_match_all('/[^A-Za-z]/', $password)) {
                    $score -= $lenght;
                }

                if (!preg_match_all('/[^0-9]/', $password)) {
                    $score -= $lenght;
                }

                echo "num_upper: {$num_upper}<br>";
                echo "num_number: {$num_number}<br>";
                echo "num_symbol: {$num_symbol}<br>";
            }
        }

        echo "password: {$password}<br>";
        echo "score: {$score}<br>";

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

        header('location:/wechat/home.php');
        exit();
    }
}
?>
