<?php

namespace Core\Helper;

class Url {

    public static function removeSufix($string, $delimitator = '.') {
        $return = explode($delimitator, $string);
        return is_array($return) ? $return[0] : $return;
    }

    public static function isRedirectUrl($url) {        
        $compareUrl[] = '/company/create-user-company/';
        $compareUrl[] = '/user/profile-image/';
        $compareUrl[] = '/user/get-image-profile/';
        $compareUrl[] = '/user/login/';
        $compareUrl[] = '/user/logout/';
        $url_final = substr(self::removeSufix($url), -1) == '/' ? self::removeSufix($url) : self::removeSufix($url) . '/';
        foreach ($compareUrl AS $compare) {
            $pregGrep = ($url_final != '/') ? preg_grep('/^' . \addcslashes($compare, '/') . '/i', array($url_final)) : false;                  
            if ($pregGrep) {                
                return false;
            }
        }        
        return true;
    }

}
