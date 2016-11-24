<?php

namespace Core\Helper;

use Core\Model\ErrorModel;

class Format {

    public static function returnData($data = false, $page = 1, $total_results = 0) {

        if (ErrorModel::getErrors()) {
            $return['error'] = ErrorModel::getErrors();
            $return['success'] = false;
        } else {
            is_array($data) && array_key_exists('data', $data) ? $return = $data : ($data ? $return['data'] = $data : $return = false);
            $total_results ? $return['total'] = $total_results : false;
            $total_results ? $return['page'] = (int) $page : false;
            $keys = is_array($return['data']) ? array_keys($return['data']) : null;
            $return['data'] ? ($return['count'] = (int) count(is_array($return['data']) ? $return['data'][array_shift($keys)] : $return['data'])) : false;
            $return['success'] = true;
        }
        return array('response' => $return);
    }

    public static function objectToSimpleObject($object) {
        $methods = get_class_methods($object);
        $simpleObject = new \stdClass();
        foreach ($methods AS $method) {
            if (preg_match('/^get/', $method)) {
                $field = strtolower(substr($method, 3, strlen($method)));
                is_object($object->$method()) ?: $simpleObject->$field = $object->$method();
            }
        }
        return $simpleObject;
    }

}
