<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 */

/**
 * Computes strength of password
 *
 * @author Matthieu Chatelan, Lassalle Loan, Wojciech Myszkorowski
 * @since 27.09.2017
 */
class PasswordMeter {
    
    private static $_instance;
    
    private function __construct() {
        
    }

    public static function get_instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }

    public function count_char($password) {
        $result = array(
            'upper' => 0,
            'lower' => 0,
            'digit' => 0,
            'symbol' => 0
        );

        $chars = str_split($password);
        foreach($chars as $char){
            if (preg_match_all('/[A-Z]/', $char)) {
                $result['upper']++;
            }

            if (preg_match_all('/[a-z]/', $char)) {
                $result['lower']++;
            }

            if (preg_match_all('/[0-9]/', $char)) {
                $result['digit']++;
            }

            if (preg_match_all('/\W/', $char)) {
                $result['symbol']++;
            }
        }

        return $result;
    }

    public function get_strength($username, $password) {
        $PASSWORD_LENGTH_MIN = 8;
        $UPPER_MIN = 4;
        $LOWER_MIN = 4;
        $DIGIT_MIN = 2;
        $SYMBOL_MIN = 2;
        $BONUS = 10;

        $lenght = strlen($password);
        $count_char = $this->count_char($password);
        $score = 0;

        // Password Lenght
        $score += $lenght < $PASSWORD_LENGTH_MIN ? $lenght : $lenght * 4;

        // Password upper
        $score += $count_char['upper'] < $UPPER_MIN ? $count_char['upper'] : $count_char['upper'] * 2;

        // Password lower
        $score += $count_char['lower'] < $LOWER_MIN ? $count_char['lower'] : $count_char['lower'] * 2;

        // Password digit
        $score += $count_char['digit'] < $DIGIT_MIN ? $count_char['digit'] : $count_char['digit'] * 4;

        // Password symbol
        $score += $count_char['symbol'] < $SYMBOL_MIN ? $count_char['symbol'] : $count_char['symbol'] * 6;

        // Bonus
        $score += $count_char['upper'] + $count_char['lower'] === $lenght ? -$BONUS : 0;
        $score += $count_char['digit'] === $lenght ? -$BONUS : 0;
        $score += strpos($password, $username) ? -$BONUS : 0;
        $score += preg_match_all('/(\S+?)\1\S*/i', $password) ? -$BONUS: 0;
        $score += $count_char['upper'] > $UPPER_MIN && 
            $count_char['lower'] > $LOWER_MIN && 
            $count_char['lower'] > $LOWER_MIN && 
            $count_char['symbol'] > $SYMBOL_MIN ? $BONUS : 0;

        if ($score < 0) {
            $score = 0;
        }

        return $score;
    }
}
?>
