<?php

namespace Core\Helper;

/*
 * https://stackoverflow.com/questions/3003145/how-to-get-the-client-ip-address-in-php
 */

class IP {

    public static function getIpAddress() {
        $client = filter_input(INPUT_SERVER, 'HTTP_CLIENT_IP');
        $forward = filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED_FOR');
        $remote = filter_input(INPUT_SERVER, 'REMOTE_ADDR');
        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }
        return $ip;        
    }

    public static function getInternalIpAddress() {
        // check for shared internet/ISP IP              

        if (!empty(filter_input(INPUT_SERVER, 'HTTP_CLIENT_IP')) && self::validateIP(filter_input(INPUT_SERVER, 'HTTP_CLIENT_IP'))) {
            return filter_input(INPUT_SERVER, 'HTTP_CLIENT_IP');
        }

        // check for IPs passing through proxies
        if (!empty(filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED_FOR'))) {
            // check if multiple ips exist in var
            if (strpos(filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED_FOR'), ',') !== false) {
                $iplist = explode(',', filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED_FOR'));
                foreach ($iplist as $ip) {
                    if (self::validateIP($ip))
                        return $ip;
                }
            } else if (self::validateIP(filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED_FOR'))) {
                return filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED_FOR');
            }
        }
        if (!empty(filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED')) && self::validateIP(filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED')))
            return filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED');
        if (!empty(filter_input(INPUT_SERVER, 'HTTP_X_CLUSTER_CLIENT_IP')) && self::validateIP(filter_input(INPUT_SERVER, 'HTTP_X_CLUSTER_CLIENT_IP')))
            return filter_input(INPUT_SERVER, 'HTTP_X_CLUSTER_CLIENT_IP');
        if (!empty(filter_input(INPUT_SERVER, 'HTTP_FORWARDED_FOR')) && self::validateIP(filter_input(INPUT_SERVER, 'HTTP_FORWARDED_FOR')))
            return filter_input(INPUT_SERVER, 'HTTP_FORWARDED_FOR');
        if (!empty(filter_input(INPUT_SERVER, 'HTTP_FORWARDED')) && self::validateIP(filter_input(INPUT_SERVER, 'HTTP_FORWARDED')))
            return filter_input(INPUT_SERVER, 'HTTP_FORWARDED');

        // return unreliable ip since all else failed
        return filter_input(INPUT_SERVER, 'REMOTE_ADDR');
    }

    /**
     * Ensures an ip address is both a valid IP and does not fall within
     * a private network range.
     */
    public static function validateIP($ip) {
        if (strtolower($ip) === 'unknown')
            return false;

        // generate ipv4 network address
        $ip = ip2long($ip);

        // if the ip is set and not equivalent to 255.255.255.255
        if ($ip !== false && $ip !== -1) {
            // make sure to get unsigned long representation of ip
            // due to discrepancies between 32 and 64 bit OSes and
            // signed numbers (ints default to signed in PHP)
            $ip = sprintf('%u', $ip);
            // do private network range checking
            if ($ip >= 0 && $ip <= 50331647)
                return false;
            if ($ip >= 167772160 && $ip <= 184549375)
                return false;
            if ($ip >= 2130706432 && $ip <= 2147483647)
                return false;
            if ($ip >= 2851995648 && $ip <= 2852061183)
                return false;
            if ($ip >= 2886729728 && $ip <= 2887778303)
                return false;
            if ($ip >= 3221225984 && $ip <= 3221226239)
                return false;
            if ($ip >= 3232235520 && $ip <= 3232301055)
                return false;
            if ($ip >= 4294967040)
                return false;
        }
        return true;
    }

}
