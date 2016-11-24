<?php

namespace Core;

use Core\Model\ErrorModel;

class DiscoveryModel {

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $_sm;
    private $params = [];
    private $method;
    private $viewMethod;
    private $rows;
    private $config;

    public function __construct(\Zend\ServiceManager\ServiceManager $sm, $method, $viewMethod, $params, $config) {
        $this->setSm($sm);
        $this->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
        $this->setMethod($method);
        $this->setViewMethod($viewMethod);
        $this->setParams($this->prepareParams($params, $method));
        $this->setConfig($config);
    }

    public function getSm() {
        return $this->_sm;
    }

    public function setSm($sm) {
        $this->_sm = $sm;
        return $this;
    }

    public function getConfig() {
        return $this->config;
    }

    public function setConfig($config) {
        $this->config = $config;
        return $this;
    }

    /**
     * Return a EntityManager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager() {
        return $this->em;
    }

    public function setParam($param, $value) {
        $this->params[$param] = $value;
    }

    public function getParam($param) {
        return $this->params[$param];
    }

    public function getParams() {
        return $this->params;
    }

    public function getViewMethod() {
        return $this->viewMethod;
    }

    public function setViewMethod($viewMethod) {
        $this->viewMethod = $viewMethod;
        return $this;
    }

    public function setParams(array $params) {
        $this->params = $params;
        return $this;
    }

    public function getMethod() {
        return $this->method;
    }

    public function setMethod($method) {
        $this->method = $method;
        return $this;
    }

    public function setEntityManager(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
        return $this;
    }

    public function prepareParams(\Zend\Http\PhpEnvironment\Request $params, $method = 'GET') {

        $_params = array();
        switch ($method) {
            case 'PUT':
            case 'DELETE':
                parse_str(file_get_contents('php://input'), $_params);
                array_merge($_params, $params->getPost()->toArray());
                break;
            case 'POST':
                $_params = $params->getPost()->toArray();
                break;
            default:
                $_params = $params->getQuery()->toArray();
                break;
        }
        return $_params;
    }

    public function getTotalResults() {
        return $this->rows;
    }

    public function discovery($entity, $entity_parent = null, $from_form = false) {
        $default_model = new Model\DefaultModel();
        $default_model->initialize($this->getSm());
        $default_model->setConfig($this->config);
        $default_model->setEntity('Entity\\' . $entity);
        ((isset($this->params['deep'])) ? $default_model->setMaxDeep($this->params['deep']) : 0);

        if (!$from_form) {
            if (!$default_model->getEntity()) {
                ErrorModel::addError(array('code' => '404', 'message' => 'Error 404: %1$s %2$s not found', 'values' => array('Entity', $default_model->getEntityName())));
                return;
            }
            switch ($this->getMethod()) {
                case 'POST':
                    $data = $default_model->insert($this->params);
                    break;
                case 'PUT':
                    $data = $default_model->edit($this->params);
                    break;
                case 'DELETE':
                    $data = $default_model->delete($this->params['id']);
                    break;
                default:
                    if ($this->getViewMethod() != 'form') {
                        $id = isset($this->params['id']) ? $this->params['id'] : null;
                        $page = isset($this->params['page']) ? $this->params['page'] : 1;
                        $limit = isset($this->params['limit']) ? $this->params['limit'] : 100;
                        if ($entity_parent) {
                            $data = $default_model->getWithParent($id, $entity_parent, $page, $limit);
                        } else {
                            $data = $default_model->get($id, $page, $limit);
                        }
                        $this->rows = $default_model->getTotalResults();
                    }
                    break;
            }
        } else {
            switch ($this->getViewMethod()) {
                case 'form':
                    $data = $default_model->form($entity, $this->params);
                    break;
            }
        }

        return $data;
    }

}
