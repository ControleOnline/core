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
            $return['data'] ? ($return['count'] = (int) count(is_array($return['data']) ? $return['data'][array_shift(array_keys($return['data']))] : $return['data'])) : false;
            $return['success'] = true;
        }
        return array('response' => $return);
    }

}
