<?php

namespace Core\Helper;

use GuzzleHttp\Client;
use Core\Helper\Config;
use Core\Helper\Format;

class Api {

    private static $nv_token = NULL;
    private static $nv_user = NULL;
    private static $nv_password = NULL;
    private static $nv_client = NULL;
    private static $nv_url = NULL;

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

    protected static function nvConnect() {
        if (!self::$nv_token) {
            $client = new Client();

            self::$nv_user = Config::getConfig('nv_user');
            self::$nv_password = Config::getConfig('nv_password');
            self::$nv_client = Config::getConfig('nv_client');
            self::$nv_url = Config::getConfig('nv_url');

            $res = $client->get(self::$nv_url . 'GerarToken', array(
                'query' => array('usuario' => self::$nv_user, 'senha' => self::$nv_password, 'cliente' => self::$nv_client)
            ));
            self::$nv_token = trim(Format::replaceXMLWords($res->getBody()));
        }
    }

    public static function nvGet($endpoint, $data) {
        $client = new Client();
        self::nvConnect();
        $data['token'] = self::$nv_token;
        return Format::xmlToArray($client->get(self::$nv_url . $endpoint, array('query' => $data))->getBody());
    }

}
