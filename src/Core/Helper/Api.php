<?php

namespace Core\Helper;

class Api {

    public static function simpleCurl($url, array $params) {
        $fields_string = self::cleanParams($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($params));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public static function cleanParams($params) {
        foreach ($params AS $key => $param) {
            if (is_array($param)) {
                $fields_string .= self::cleanParams($param);
            } else {
                $fields_string .= urlencode(trim($key)) . '=' . urlencode(trim($param)) . '&';
            }
        }
        return $fields_string;
    }

}
