<?php

namespace Core\Helper;

class LazyLoad {

    protected static $content;
    protected static $config;

    private function __construct() {
        
    }

    protected static function setContent($content) {
        self::$content = $content;
        return $this;
    }

    public static function getContent() {
        return self::$content;
    }

    /**
     *  Adjust this regex to not get images inside a script tag
     * Example:
     * <script>var a = '<img src="test.png">';</script>            
     */
    protected static function removeImagesFromScripts() {
        foreach (self::$config['LazyLoadExcludeTags'] as $no) {
            $htmlContent = self::getContent();
            $regex = '/<' . $no . '((?:.)*?)>((?:.)*?)<\/' . $no . '>/smix';
            $content = preg_replace_callback($regex, function($script) use ($no) {
                if ($script[2]) {
                    $regimg = '/<img((?:.)*?)>/smix';
                    $img = preg_replace_callback($regimg, function($i) {
                        return '<noimg' . $i[1] . '>';
                    }, $script[2]);
                    return $img ? '<' . $no . $script[1] . '>' . $img . '</' . $no . '>' : $script[0];
                } else {
                    return $script[0];
                }
            }, $htmlContent);
            self::setContent($content ?: $htmlContent);
        }
    }

    protected static function returnImagesFromScripts() {
        foreach (self::$config['LazyLoadExcludeTags'] as $no) {
            $htmlContent = self::getContent();
            $regex = '/<' . $no . '((?:.)*?)>((?:.)*?)<\/' . $no . '>/smix';
            $content = preg_replace_callback($regex, function($script) use ($no) {
                if ($script[2]) {
                    $regimg = '/<noimg((?:.)*?)>/smix';
                    $img = preg_replace_callback($regimg, function($i) {
                        return '<img' . $i[1] . '>';
                    }, $script[2]);
                    return $img ? '<' . $no . $script[1] . '>' . $img . '</' . $no . '>' : $script[0];
                } else {
                    return $script[0];
                }
            }, $htmlContent);
            self::setContent($content ?: $htmlContent);
        }
    }

    protected static function normalizeAttributes(array $attributes = array()) {
        foreach ($attributes AS $key => $att) {
            if (strtolower($key) == 'class') {
                $att = $att . ' ' . self::$config['LazyLoadClass'];
            }
            if (strtolower($key) == 'src') {
                //$att = Url::normalizeUrl($att);
                $return['img'] .= ' ' . $key . '="' . $att . '"';
                $return['lazy_img'] .= ' ' . $key . '="' . self::$config['LazyLoadPlaceHolder'] . '"';
                $key = 'data-ll';
            } else {
                $return['img'] .= ' ' . $key . '="' . $att . '"';
            }
            if (!in_array(strtolower($key), self::$config['LazyLoadOnlyOnNoScript'])) {
                $return['lazy_img'] .= ' ' . $key . '="' . $att . '"';
            }
        }
        if (!array_key_exists('class', $attributes)) {
            $return['img'] .= ' class="' . self::$config['LazyLoadClass'] . '"';
        }
        return $return;
    }

    protected static function prepareImg($script) {
        $regex_img = '/(\S+)=["\']((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']/';
        preg_match_all($regex_img, $script[1], $matches);
        if (isset($matches[1]) && isset($matches[2])) {
            foreach ($matches[1] AS $k => $key) {
                $attributes[trim($key)] = trim($matches[2][$k]);
            }
        }
        if (isset($attributes) && is_array($attributes)) {
            $img = '<img';
            $lazy_img = '<img';
            $att = self::normalizeAttributes($attributes);
            $img .= $att['img'];
            $lazy_img .= $att['lazy_img'];
            $img .= '>';
            $lazy_img .= '>';
            $content_img = $lazy_img;
            $content_img .= '<noscript class="ns-ll">';
            $content_img .= $img;
            $content_img .= '</noscript>';
            return $content_img;
        } else {
            return $matches[0];
        }
    }

    protected static function ignoreLazyLoad($htmlContent) {
        return preg_match('/ignore-ll=["|\']true["|\']/', $htmlContent);
    }

    public static function imgLazyLoad($htmlContent, $config) {
        self::setContent($htmlContent);
        self::$config = $config;
        if (self::$config['LazyLoadImages'] && !self::ignoreLazyLoad($htmlContent)) {
            self::removeImagesFromScripts();
            $regex = '/<img((?:.)*?)>/smix';
            $content = preg_replace_callback($regex, function($script) {
                return LazyLoad::prepareImg($script);
            }, $htmlContent);
            self::setContent($content ?: $htmlContent);
            self::returnImagesFromScripts();
        }
        return self::getContent();
    }

}
