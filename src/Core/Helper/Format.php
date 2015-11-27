<?php

namespace Core\Helper;

use Core\Model\ErrorModel;

class Format {

    public static function returnData($data = false) {
        if (ErrorModel::getErrors()) {
            $return['error'] = ErrorModel::getErrors();
            $return['success'] = false;
        } else {
            array_key_exists('data', $data) ? $return = $data : ($data ? $return['data'] = $data : false);
            $return['success'] = true;
        }
        return array('response' => $return);
    }

}
