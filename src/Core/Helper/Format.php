<?php

namespace Core\Helper;

use Core\Model\ErrorModel;
use Zend\View\Variables;

class Format {

    public static function removeUrlParameters($url) {
        return parse_url($url, PHP_URL_PATH);
    }

    public static function getModulePath($module) {
        if ($module) {
            $reflector = new \ReflectionClass('\\' . $module . '\\Module');
            return dirname($reflector->getFileName());
        }
    }

    public static function returnData($data = false, $convert_to_array = false, $page = 1, $total_results = 0) {

        if (ErrorModel::getErrors()) {
            $return['error'] = ErrorModel::getErrors();
            $return['success'] = false;
        } else {
            if ($data instanceof Variables) {
                $array = $data->getArrayCopy();
                $return['data'] = array_key_exists('response', $array) && array_key_exists('data', $array['response']) ? $array['response']['data'] : false;
            } elseif (is_array($data) && array_key_exists('data', $data)) {
                $return = $data;
            } elseif (is_array($data) && array_key_exists('response', $data) && array_key_exists('data', $data['response'])) {
                $return = array('data' => $data['response']['data']);
            } elseif ($data) {
                $return['data'] = $data;
            } else {
                $return = false;
            }

            $total_results ? $return['total'] = $total_results : false;
            $total_results ? $return['page'] = (int) $page : false;
            $keys = is_array($return['data']) ? array_keys($return['data']) : null;
            $return['data'] ? ($return['count'] = (int) count(is_array($return['data']) ? $return['data'][array_shift($keys)] : $return['data'])) : false;
            $return['success'] = true;
        }
        return array('response' => $return);
    }

}
