<?php

namespace Core\Helper;

class Size {

    public static function parseSize($parseSize) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $parseSize);
        $size = preg_replace('/[^0-9\.]/', '', $parseSize);
        if ($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }

    public static function formatBytes($bytes, $decimals = 2) {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }

}
