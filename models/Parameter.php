<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 */
 
/**
 * Uses to create prepared SQL query
 *
 * @author Matthieu Chatelan, Lassalle Loan, Wojciech Myszkorowski
 * @since 27.09.2017
 */
class Parameter {

    private $_name;
    private $_value;
    private $_pdo_type;
    
    public function __construct($name, $variable, $pdo_type) {
        $this->_name = $name;
        $this->_value = $variable;
        $this->_pdo_type = $pdo_type;
    }

    public function get_name() {
        return $this->_name;
    }

    public function set_name($name) {
        $this->$_name = $name;
    }

    public function get_value() {
        return $this->_value;
    }

    public function set_value($variable) {
        $this->_value = $variable;
    }

    public function get_pdo_type() {
        return $this->_pdo_type;
    }

    public function set_pdo_type($pdo_type) {
        $this->_pdo_type = $pdo_type;
    }

    public function __toString() {
        return $this->_name.' '.$this->_value;
    }
}
?>
