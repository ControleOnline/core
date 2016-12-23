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
    protected static $publicVendorBasepath = '/vendor/';
    protected static $requireJsFiles = [];

    /**
     * @var \Zend\View\Helper\HeadScript
     */
    protected static $x;

    public static function addJs(\Zend\View\Renderer\RendererInterface $renderer, $src, $type = 'text/javascript', $attrs = array()) {
        self::$renderer = $renderer;
        self::$renderer->headScript()->setAllowArbitraryAttributes(true)->appendFile(self::$renderer->basePath($src . '?v=' . self::getSystemVersion()), $type, $attrs);
    }

    public static function addJsLib(\Zend\View\Renderer\RendererInterface $renderer, $src, $type = 'text/javascript', $attrs = array()) {
        $attrs['data-type'] = 'lib';
        self::$renderer = $renderer;
        self::$renderer->headScript()->setAllowArbitraryAttributes(true)->appendFile(self::$renderer->basePath($src . '?v=' . self::getSystemVersion()), $type, $attrs);
    }

    public static function addCssLib(\Zend\View\Renderer\RendererInterface $renderer, $href, $media = 'screen', $conditionalStylesheet = '', $extras = array()) {
        $extras['data-type'] = 'lib';
        self::$renderer = $renderer;
        self::$renderer->headLink()->appendStylesheet(self::$renderer->basePath($href . '?v=' . self::getSystemVersion()), $media, $conditionalStylesheet, $extras);
    }

    public static function addCss(\Zend\View\Renderer\RendererInterface $renderer, $href, $media = 'screen', $conditionalStylesheet = '', $extras = array()) {
        self::$renderer = $renderer;
        self::$renderer->headLink()->appendStylesheet(self::$renderer->basePath($href . '?v=' . self::getSystemVersion()), $media, $conditionalStylesheet, $extras);
    }

    public static function addDefaultLibs(\Zend\View\Renderer\RendererInterface $renderer) {
        /* Removing to use AMD load with requirejs
          self::addJsLib($renderer, '/vendor/jquery/dist/jquery.min.js');
          self::addJsLib($renderer, '/vendor/bootstrap/dist/js/bootstrap.min.js', 'text/javascript', array('async' => true, 'defer' => true));
          self::addJsLib($renderer, '/vendor/controleonline-core-js/dist/js/LazyLoad.js', 'text/javascript', array('async' => true, 'defer' => true));
         */
        self::addJsLib($renderer, self::$publicVendorBasepath . 'requirejs/require.js', 'text/javascript', array('data-main' => self::$publicVendorBasepath . 'controleonline-core-js/dist/js/Core.js?v=' . self::getSystemVersion()));
        self::addCssLib($renderer, '/vendor/bootstrap/dist/css/bootstrap.min.css');
        self::addCssLib($renderer, '/vendor/controleonline-core-js/dist/css/LazyLoad.css');
        self::addCssLib($renderer, '/vendor/fontawesome/css/font-awesome.min.css');
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

        //self::addJsFile($renderer, DIRECTORY_SEPARATOR . self::$routes['module'] . '.js');
        self::addRequireJsFile(DIRECTORY_SEPARATOR . self::$routes['module'], self::$routes['module']);
        //self::addJsFile($renderer, DIRECTORY_SEPARATOR . self::$routes['module'] . DIRECTORY_SEPARATOR . self::$routes['controller'] . '.js');
        self::addRequireJsFile(DIRECTORY_SEPARATOR . self::$routes['module'] . DIRECTORY_SEPARATOR . self::$routes['controller'], self::$routes['module'] . '-' . self::$routes['controller']);
        //self::addJsFile($renderer, DIRECTORY_SEPARATOR . self::$routes['module'] . DIRECTORY_SEPARATOR . self::$routes['controller'] . DIRECTORY_SEPARATOR . self::$routes['action'] . '.js');
        self::addRequireJsFile(DIRECTORY_SEPARATOR . self::$routes['module'] . DIRECTORY_SEPARATOR . self::$routes['controller'] . DIRECTORY_SEPARATOR . self::$routes['action'], self::$routes['module'] . '-' . self::$routes['controller'] . '-' . self::$routes['action']);

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

    public static function addRequireJsFile($src, $name) {
        $path = DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'modules';
        if (is_file(self::$basepath . $path . $src . '.js')) {
            self::$requireJsFiles[$name] = '..' . $path . $src . '?v=' . self::getSystemVersion();
        }
    }

    public static function getRequireJsFiles() {
        return self::$requireJsFiles;
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
        return isset($version) && $version ? $version : date('Y-m-d-H-m-i');
    }

}
