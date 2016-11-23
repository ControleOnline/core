<?php

namespace Core\Helper;

use Core\DiscoveryRoute;

class Header {

    /**
     * @var \Zend\View\Renderer\RendererInterface
     */
    protected static $renderer;
    protected static $routes;
    protected static $basepath;

    public static function addJs(\Zend\View\Renderer\RendererInterface $renderer, $src, $type = 'text/javascript', $attrs = array()) {
        self::$renderer = $renderer;
        self::$renderer->headScript()->appendFile(self::$renderer->basePath($src), $type, $attrs);
    }

    public static function addCss(\Zend\View\Renderer\RendererInterface $renderer, $href, $media = 'screen', $conditionalStylesheet = '', $extras = array()) {
        self::$renderer = $renderer;
        self::$renderer->headLink()->appendStylesheet(self::$renderer->basePath($href), $media, $conditionalStylesheet, $extras);
    }

    public static function addDefaultHeaderFiles(\Zend\View\Renderer\RendererInterface $renderer, $default_route, $uri) {
        $DiscoveryRoute = new DiscoveryRoute($default_route);
        $DiscoveryRoute->setUrl($uri);
        $routes = $DiscoveryRoute->getRoute();
        $c = explode('\\', $routes['controller']);
        $controller = strtolower(end($c));
        self::$routes['module'] = strtolower($routes['module']);
        self::$routes['controller'] = substr($controller, -10) == 'controller' ? substr($controller, 0, -10) : $controller;
        self::$routes['action'] = strtolower($routes['action']);
        self::$basepath = getcwd() . DIRECTORY_SEPARATOR . 'public';

        self::addJsFile($renderer, DIRECTORY_SEPARATOR . self::$routes['module'] . '.js');
        self::addJsFile($renderer, DIRECTORY_SEPARATOR . self::$routes['module'] . DIRECTORY_SEPARATOR . self::$routes['controller'] . '.js');
        self::addJsFile($renderer, DIRECTORY_SEPARATOR . self::$routes['module'] . DIRECTORY_SEPARATOR . self::$routes['controller'] . DIRECTORY_SEPARATOR . self::$routes['action'] . '.js');

        self::addCssFile($renderer, DIRECTORY_SEPARATOR . self::$routes['module'] . '.css');
        self::addCssFile($renderer, DIRECTORY_SEPARATOR . self::$routes['module'] . DIRECTORY_SEPARATOR . self::$routes['controller'] . '.css');
        self::addCssFile($renderer, DIRECTORY_SEPARATOR . self::$routes['module'] . DIRECTORY_SEPARATOR . self::$routes['controller'] . DIRECTORY_SEPARATOR . self::$routes['action'] . '.css');
    }

    protected static function addJsFile(\Zend\View\Renderer\RendererInterface $renderer, $src) {
        $path = DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'modules';
        if (is_file(self::$basepath . $path . $src)) {
            self::addJs($renderer, $path . $src . '?v=' . self::getSystemVersion());
        }
    }

    protected static function addCssFile(\Zend\View\Renderer\RendererInterface $renderer, $href) {
        $path = DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'modules';
        if (is_file(self::$basepath . $path . $href)) {
            self::addCss($renderer, $path . $href . '?v=' . self::getSystemVersion());
        }
    }

    protected static function getSystemVersion() {
        if (is_file(getcwd() . '.version')) {
            $contents = file_get_contents(getcwd() . '.version');
            $version = $contents ? trim(array_shift(array_values(preg_split('/\r\n|\r|\n/', $contents, 2)))) : false;
        }
        return $version ?: date('Y-m-d-H');
    }

}
