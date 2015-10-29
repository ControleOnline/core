<?php

namespace Core\Model;

class ErrorModel {

    protected static $_errors = array();

    public static function getErrors() {
        return self::$_errors;
    }

    public static function addError($error, array $values = array()) {
        self::$_errors = array_merge(is_array($error) ? $error : array(vsprintf($error, $values)), self::$_errors);
    }

}
