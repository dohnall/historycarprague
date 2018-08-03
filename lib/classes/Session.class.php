<?php

class Session {

    private $data = array();
    private static $instance;

    public function __construct($section) {
        session_start();
        $this->data =& $_SESSION[$section];
    }

    public static function getInstance($section) {
        if (!isset(self::$instance)) {
            $classname = __CLASS__;
            self::$instance = new $classname($section);
        }
        return self::$instance;
    }

    public function __set($name, $val) {
        $this->data[$name] = $val;
    }

    public function __get($name) {
        if(isset($this->data[$name])) {
            return $this->data[$name];
        } else {
            return null;
        }
    }

    public function __isset($name) {
        return isset($this->data[$name]);
    }

    public function __unset($name) {
        if(isset($this->data[$name])) {
            unset($this->data[$name]);
        }
    }

    public function clear() {
        $this->data = array();
    }

}
