<?php

namespace Core;

use Zend\Stdlib\RequestInterface as Request;
use Core\Helper\Url;

class DiscoveryRoute {

    protected static $_url;
    protected $_controller;
    protected $_module;
    protected $_action;
    protected $_entity;
    protected $_vars;
    protected $_defaultRoute;
    protected $_EntityChildren;

    public function __construct($defaultRoute) {
        $this->setDefaultRoute($defaultRoute);
    }

    public function getRoute() {        
        $return = array_merge($this->getDefaultRoute(), array(
            'path' => implode('/', $this->getUrl())
        ));
        return $this->discoveryRoute($return);
    }

    public function setGetParams(Request $request) {
        $params = $this->getUrl();
        $qtde = count($params);
        for ($i = 0; $i < $qtde; $i += 2) {
            $key = Url::removeSufix($params[$i]);
            $value = isset($params[$i + 1]) ? Url::removeSufix($params[$i + 1]) : null;
            $request->getQuery()->set($key, $value);
        }
    }

    private function getMethodType($uri, array $search = array('json', 'form')) {
        foreach ($search AS $compare) {
            $c = '.' . $compare;
            $method = substr_compare($uri->getPath(), $c, strlen($uri->getPath()) - strlen($c), strlen($c)) === 0;
            $return = $method ? $compare : null;
        }
        return $return;
    }

    public function setMethod(Request $request, $uri) {
        $method = $this->getMethodType($uri);
        $request->getQuery()->set('viewMethod', strtolower($method ?: 'html'));
        $request->getQuery()->set('method', strtoupper($request->getQuery()->get('method') ?: $request->getPost()->get('method') ?: filter_input(INPUT_SERVER, 'REQUEST_METHOD')));
    }

    protected function formatClass($class, $type, $module = null) {
        if ($module) {
            return '\\' . $this->camelCase($module) . '\\' . $this->camelCase($type) . '\\' . $this->camelCase($class);
        } else {
            return '\\Core\\' . $this->camelCase($type) . '\\' . $this->camelCase($class);
        }
    }

    protected function discoveryByController($routes) {
        $defaultRoute = $this->getDefaultRoute();
        $module = $this->camelCase((isset($routes[0]) ? Url::removeSufix($routes[0]) : $defaultRoute['module']));
        $class_name = $this->camelCase((isset($routes[1]) ? Url::removeSufix($routes[1]) : $defaultRoute['controller']));
        $controller = $this->getControllerName($class_name, $module);
        if (class_exists($controller)) {
            $this->setModule($module);
            $this->setController($controller);
        }
    }

    protected function getControllerName($class_name, $module) {
        $defaultRoute = $this->getDefaultRoute();
        $class = $this->formatClass($class_name, 'Controller', $module) . 'Controller';
        $url = $this->getUrl();
        if (!class_exists($class)) {
            $class = $this->formatClass($defaultRoute['controller'], 'Controller', $module) . 'Controller';
            //unset($url[0]);
        } else {
            //$url = $this->removeClassFromUrl($this->getUrl(), $module);
            $url = $this->removeClassFromUrl($this->getUrl(), $class_name);
        }
        $this->setUrl($url);
        return $class;
    }

    protected function discoveryAction() {
        $defaultRoute = $this->getDefaultRoute();
        $url = $this->getUrl();
        $action = lcfirst($this->camelCase((isset($url[1]) ? Url::removeSufix($url[1]) : $defaultRoute['action'])));
        $class = $this->getController();
        $testClass = new $class();
        if (method_exists($testClass, $action . 'Action')) {
            $this->setAction($action);
            $url = $this->removeClassFromUrl($this->getUrl(), $action);
            $this->setUrl($url);
        } else {
            $this->setAction($defaultRoute['action']);
        }
    }

    protected function discoveryRoute($default) {
        $routes = $this->getUrl();
        $this->discoveryByController($routes);
        if ($this->getController()) {
            $this->discoveryAction($routes);
        }
        $return = array(
            'module' => $this->camelCase($this->getModule()),
            'controller' => $this->getController(),
            'action' => $this->camelCase($this->getAction()),
            'entity' => $this->camelCase($this->getEntity()),
            'entity_children' => $this->camelCase($this->getEntityChildren())
        );
        
        return array_merge($default, $return);
    }

    protected function camelCase($string) {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    public function getController() {
        return $this->_controller;
    }

    public function getModule() {
        return $this->_module;
    }

    public function getAction() {
        return $this->_action;
    }

    public function getEntity() {
        return $this->_entity;
    }

    public function getVars() {
        return $this->_vars;
    }

    public function getUrl() {
        return array_values(self::$_url);
    }

    public function setController($controller) {
        $this->_controller = $controller;
        return $this;
    }

    public function setModule($module) {
        $this->_module = $module;
        return $this;
    }

    public function setAction($action) {
        $this->_action = $action;
        return $this;
    }

    public function setEntity($entity) {
        $this->_entity = $entity;
        return $this;
    }

    public function setVars($vars) {
        $this->_vars = $vars;
        return $this;
    }

    public function setUrl($url) {
        if (is_array($url)) {
            $routes = $url;
        } else {
            $routes = array_filter(explode('/', $url));
        }
        self::$_url = array_values($routes);
        return $this;
    }

    public function getDefaultRoute() {
        return $this->_defaultRoute;
    }

    public function setDefaultRoute($defaultRoute) {
        $this->_defaultRoute = $defaultRoute;
        return $this;
    }

    public function getEntityChildren() {
        return $this->_EntityChildren;
    }

    public function setEntityChildren($EntityChildren) {
        $this->_EntityChildren = $EntityChildren;
        return $this;
    }

    protected function removeClassFromUrl($url, $name = null, $index = 0) {
        if (is_array($url) && array_key_exists($index, $url) && (!$name || strtolower($name) == strtolower($url[$index]))) {
            unset($url[$index]);
        }
        return $url;
    }

}
