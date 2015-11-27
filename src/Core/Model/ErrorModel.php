<?php

namespace Core\Model;

class ErrorModel {

    protected static $_errors = array();

    public static function getErrors() {
        return self::$_errors;
    }

    public static function addError($error, array $values = array()) {

        $return = $error;
        if (!is_array($error)) {
            $return = array('code' => self::discoveryErrorCode($error), 'message' => vsprintf($error, $values));
        } elseif (!isset($error['message']) || !isset($error['code'])) {
            foreach ($error AS $key => $e) {
                $return[$key]['code'] = self::discoveryErrorCode($e);
                $return[$key]['message'] = vsprintf($e, $values);
            }
        } else {
            $return['code'] = $error['code'];
            $return['message'] = vsprintf($error['message'], $values);
        }
        self::$_errors[] = $return;
    }

    public static function discoveryErrorCode($error) {
        return 0;
    }

    public function __destruct() {
        foreach (self::getErrors() AS $error) {
            throw new \Exception($error);
        }
    }

}
