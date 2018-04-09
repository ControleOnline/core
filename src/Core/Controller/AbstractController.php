<?php

namespace Core\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Core\DiscoveryModel;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;
use Core\Helper\View;

class AbstractController extends AbstractActionController {

    protected $_allowed_view_methods = array('json', 'html', 'form');

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em;

    /**
     * @var \Zend\View\Model\ViewModel
     */
    public $_view;

    /**
     * @var \Zend\View\Renderer\RendererInterface
     */
    protected $_renderer;
    protected $_allowed_methods = array('GET', 'POST', 'PUT', 'DELETE', 'FORM');
    protected $_method;
    protected $_viewMethod;
    protected $_model;
    protected $_entity_children;
    protected $_entity;
    protected $_config;
    public $_module_name;
    public $_action_name;

    public function setEntityManager(\Doctrine\ORM\EntityManager $em) {
        $this->_em = $em;
        return $this;
    }

    public function setViewType($view) {
        $this->_view = $view;
        return $this;
    }

    /**
     * Return a EntityManager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager() {
        return $this->_em;
    }

    public function onDispatch(MvcEvent $e) {
        $this->_action_name = \Core\Helper\Format::camelCaseDecode($e->getRouteMatch()->getParam('action'));
        $this->initialize();
        return parent::onDispatch($e);
    }

    protected function initialize() {
        $class = explode('\\', get_class($this));
        $method_request = strtoupper($this->params()->fromQuery('method') ? : filter_input(INPUT_SERVER, 'REQUEST_METHOD'));
        $viewMethod_request = $this->detectViewMethod();
        $this->_module_name = strtolower(substr(get_class($this), 0, strpos(get_class($this), '\\')));
        $this->module_name = strtolower(array_shift($class));
        $this->_method = in_array($method_request, $this->_allowed_methods ? : array()) ? $method_request : 'GET';
        $this->_viewMethod = in_array($viewMethod_request, $this->_allowed_view_methods) ? $viewMethod_request : 'html';
        $this->_view = ($this->_viewMethod == 'json') ? new JsonModel() : new ViewModel();
        $this->_view->module_name = $this->_module_name;
        $this->_view->action_name = $this->_action_name;
        $this->_model = new DiscoveryModel($this->serviceLocator, $this->_method, $this->_viewMethod, $this->getRequest(), $this->_config['Core']);
        $this->_entity_children = $this->params('entity_children');
        $this->_entity = $this->params('entity');
        $this->_renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');            
        View::setDefaultVariables($this, $this->serviceLocator);
    }

    protected function detectViewMethod() {
        $request = $this->getRequest();
        $uri = $request->getUri()->getPath();
        $viewMethod_request = null;
        foreach ($this->_allowed_view_methods AS $compare) {
            $return = substr_compare($uri, '.' . $compare, strlen($uri) - strlen('.' . $compare), strlen('.' . $compare)) === 0;
            if ($return) {
                $viewMethod_request = $compare;
            }
        }
        return $viewMethod_request ? : strtolower($this->params()->fromQuery('viewMethod')) ? : 'html';
    }

}
