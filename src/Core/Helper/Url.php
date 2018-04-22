<?php

namespace Core\Helper;

class Url {

    protected static $compareUrl;

    public static function removeSufix($string, $delimitator = '.') {
        $return = explode($delimitator, $string);
        return is_array($return) ? $return[0] : $return;
    }

    public static function getDomain() {
        return filter_input(INPUT_SERVER, 'HTTP_HOST') ? : $_SERVER['HTTP_HOST'];
    }

    public static function addDefaultUrlToNoLogin($module) {
        $module = in_array(strtolower($module), array('carrier', 'client', 'company', 'provider', 'salesman', 'corporate')) ? $module : 'user';
        self::$compareUrl[] = '/' . $module . '/profile-image/';
        self::$compareUrl[] = '/' . $module . '/get-image-profile/';
        self::$compareUrl[] = '/' . $module . '/login/';
        self::$compareUrl[] = '/' . $module . '/logout/';
        self::$compareUrl[] = '/' . $module . '/forgot-password/';
        self::$compareUrl[] = '/' . $module . '/create-account/';
        self::$compareUrl[] = '/' . $module . '/create-corporate-account/';
    }

    public static function isRedirectUrl($url) {
        self::$compareUrl[] = '/assets';
        self::$compareUrl[] = '/company/create-user-company/';
        self::$compareUrl[] = '/company/create-corporate-user-company/';
        self::$compareUrl[] = '/sales/shipping-quote';
        self::$compareUrl[] = '/carrier/search-city-destination';
        self::$compareUrl[] = '/carrier/search-city-origin';
        self::$compareUrl[] = '/carrier/search-product-type';
        self::$compareUrl[] = '/sales/mail-invoice';
        self::$compareUrl[] = '/carrier/search-city-from-cep';
        self::$compareUrl[] = '/corporate/conference';
        $url_final = substr(self::removeSufix($url), -1) == '/' ? self::removeSufix($url) : self::removeSufix($url) . '/';
        foreach (self::$compareUrl AS $compare) {
            $pregGrep = ($url_final != '/') ? preg_grep('/^' . \addcslashes($compare, '/') . '/i', array($url_final)) : false;
            if ($pregGrep) {
                return false;
            }
        }
        return true;
    }

}
