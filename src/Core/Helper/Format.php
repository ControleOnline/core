<?php

namespace Core\Helper;

use Core\Model\ErrorModel;

class Format {

    public static function returnData($data = false, $page = 1, $total_results = 0) {

        if (ErrorModel::getErrors()) {
            $return['error'] = ErrorModel::getErrors();
            $return['success'] = false;
        } else {
            array_key_exists('data', $data) ? $return = $data : ($data ? $return['data'] = $data : false);
            $total_results ? $return['total'] = $total_results : false;
            $total_results ? $return['page'] = (int) $page : false;
            $return['count'] = (int) count($return['data'][array_shift(array_keys($return['data']))]);
            $return['success'] = true;
        }
        return array('response' => $return);
    }

}
