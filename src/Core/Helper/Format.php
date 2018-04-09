<?php

namespace Core\Helper;

use Core\Model\ErrorModel;
use Zend\View\Variables;

class Format {

    private static $__objectCount;

    public static function maskNumber($mask, $str) {

        $str = str_replace(" ", "", $str);
        while (strlen($str) < substr_count($mask, '#')) {
            $str = '0' . $str;
        }

        for ($i = 0; $i < strlen($str); $i++) {
            $mask[strpos($mask, "#")] = $str[$i];
        }

        return $mask;
    }

    public static function removeUrlParameters($url) {
        return parse_url($url, PHP_URL_PATH);
    }

    public static function getModulePath($module) {
        if ($module) {
            $reflector = new \ReflectionClass('\\' . $module . '\\Module');
            return dirname($reflector->getFileName());
        }
    }

    public static function searchNormalize($string) {
        return trim(preg_replace("/[^a-zA-Z0-9\s]+/", "", preg_replace(array('/(á|à|ã|â|ä)/', '/(Á|À|Ã|Â|Ä)/', '/(é|è|ê|ë)/', '/(É|È|Ê|Ë)/', '/(í|ì|î|ï)/', '/(Í|Ì|Î|Ï)/', '/(ó|ò|õ|ô|ö)/', '/(Ó|Ò|Õ|Ô|Ö)/', '/(ú|ù|û|ü)/', '/(Ú|Ù|Û|Ü)/', '/(ñ)/', '/(Ñ)/', '/(ç)/', '/(Ç)/'), array('a', 'A', 'e', 'E', 'i', 'I', 'o', 'O', 'u', 'U', 'n', 'N', 'c', 'C'), $string)));
    }

    public static function camelCaseDecode($string, $separator = '-') {
        return strtolower(preg_replace('/(?<=\d)(?=[A-Za-z])|(?<=[A-Za-z])(?=\d)|(?<=[a-z])(?=[A-Z])/', $separator, $string));
    }

    public static function onlyNumbers($string) {
        return preg_replace("/[^0-9]/", "", $string);
    }

    public static function realToCurrency($number) {

        $checkNumber = explode(' ', str_replace('%', '', $number));
        foreach ($checkNumber AS $n) {
            $checked = str_replace(array('.', ','), array('', '.'), $n);
            if ($checked > 0 && is_numeric($checked)) {
                $return = $checked;
            }
        }

        return $return? : 0;
    }

    public static function returnData($data = false, $convert_to_array = false, $page = 1, $total_results = 0) {

        if (is_array($data) && $data['error']) {
            $return['error'] = $data['error'];
            $return['success'] = false;
        } else if (ErrorModel::getErrors()) {
            $return['error'] = ErrorModel::getErrors();
            $return['success'] = false;
        } else {
            if ($data instanceof Variables) {
                //$array = $data->getArrayCopy();                
                $array = (array) self::formatEntity($data);
                $return['data'] = array_key_exists('data', $array) ? $array['data'] : (array_key_exists('response', $array) && array_key_exists('data', $array['response']) ? $array['response']['data'] : false);
            } elseif (is_array($data) && array_key_exists('data', $data)) {
                $return = $data;
            } elseif (is_array($data) && array_key_exists('response', $data) && array_key_exists('data', $data['response'])) {
                $return = array('data' => $data['response']['data']);
            } elseif ($data) {
                $return['data'] = self::formatEntity($data);
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

    public static function formatEntity($entities) {
        if (is_array($entities)) {
            foreach ($entities AS $key => $entity) {
                $return[strtolower($key)] = self::formatEntity($entity);
            }
        } else {
            if (is_object($entities)) {
                self::$__objectCount ++;
                $class = new \ReflectionClass(get_class($entities));
                $className = $class->getNamespaceName();
                echo get_class($entities) . $className;
                echo '<br>';
                if ($className == 'Core\Entity') {
                    foreach (get_class_methods($entities) AS $method) {
                        if (substr($method, 0, 3) == 'get') {
                            $content = $entities->$method();
                            if (is_object($content)) {
                                if (get_class($content) == 'Doctrine\ORM\PersistentCollection') {
                                    foreach ($content AS $key => $c) {
                                        if (count($c) > 50 || self::$__objectCount > 50) {
                                            $r[strtolower($key)] = $c->getId();
                                        } else {
                                            $r[strtolower($key)] = self::formatEntity($c);
                                        }
                                    }
                                    $content = $r;
                                } else {
                                    $content = $content->getId();
                                }
                            }
                            $return[strtolower(substr($method, 3, strlen($method)))] = $content;
                        }
                    }
                }
            } else {
                $return = $entities;
            }
        }

        return (object) $return;
    }

}
