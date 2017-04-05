<?php

namespace Core\Helper;

class Url {

    public static function removeSufix($string, $delimitator = '.') {
        $return = explode($delimitator, $string);
        return is_array($return) ? $return[0] : $return;
    }

    public static function isRedirectUrl($url) {
        $compareUrl[] = '/company/create/';
        $compareUrl[] = '/company/create-user-company/';
        $compareUrl[] = '/user/profile-image/';
        $compareUrl[] = '/user/login/';        
        $compareUrl[] = '/user/logout/';
        $pregGrep = ($url != '/') ? preg_grep('/^' . \addcslashes(self::removeSufix($url) . '/', '/') . '/i', $compareUrl) : false;
        return $pregGrep ? false : true;
    }

}
