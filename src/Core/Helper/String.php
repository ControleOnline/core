<?php

namespace Core\Helper;

class String {

    public static function decamelize($word) {
        return preg_replace(
                '/(^|[a-z])([A-Z])/e', 'strtolower(strlen("\\1") ? "\\1_\\2" : "\\2")', $word
        );
    }

    public static function camelize($word) {
        return preg_replace('/(^|_)([a-z])/e', 'strtoupper("\\2")', $word);
    }

}
