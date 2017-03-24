<?php

namespace Core\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Core\DiscoveryModel;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;

class AbstractController extends AbstractActionController {

    protected $_allowed_view_methods = array('json', 'html', 'form');

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $_em;

    /**
     * @var Zend\View\Model\ViewModel
     */
    protected $_view;

    /**
     * @var Zend\View\Renderer\RendererInterface
     */
    protected $_renderer;

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
        $this->initialize();
        return parent::onDispatch($e);
    }

    protected function initialize() {
        $method_request = strtoupper($this->params()->fromQuery('method') ?: filter_input(INPUT_SERVER, 'REQUEST_METHOD'));
        $viewMethod_request = $this->detectViewMethod();
        $this->_method = in_array($method_request, $this->_allowed_methods ?: array()) ? $method_request : 'GET';
        $this->_viewMethod = in_array($viewMethod_request, $this->_allowed_view_methods) ? $viewMethod_request : 'html';
        $this->_view = ($this->_viewMethod == 'json') ? new JsonModel() : new ViewModel();
        $this->_model = new DiscoveryModel($this->serviceLocator, $this->_method, $this->_viewMethod, $this->getRequest(), $this->_config['Core']);
        $this->_entity_children = $this->params('entity_children');
        $this->_entity = $this->params('entity');
        $this->_renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
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
        return $viewMethod_request ?: strtolower($this->params()->fromQuery('viewMethod')) ?: 'html';
    }

}
