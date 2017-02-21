<?php

namespace Core\Controller;

use Core\Controller\AbstractController;
use Core\Helper\Format;


class DefaultController extends AbstractController {

    protected $_allowed_methods = array('GET', 'POST', 'PUT', 'DELETE', 'FORM');
    protected $_allowed_view_methods = array('json', 'html', 'form');
    protected $_method;
    protected $_viewMethod;
    protected $_model;
    protected $_entity_children;
    protected $_entity;
    protected $_config;

    public function setConfig($config) {
        $this->_config = $config;
    }



    private function getForm($entity_id = null) {
        $return = [];
        $id = $entity_id ?: $this->params()->fromQuery('id');
        $this->_model->setViewMethod('form');
        $this->_model->setParam('id', $id);
        $this->_view->setTerminal(true);
        if ($this->_entity) {
            $return = $this->_model->discovery($this->_entity, null, true);
        }
        if ($this->_entity_children) {
            $return['id'] = $id;
            $return['children'] = $this->_model->discovery($this->_entity_children, $this->_entity, true);
        }
        $return['method'] = $id ? 'PUT' : 'POST';
        return $return;
    }

    private function alterData() {
        return Format::returnData($this->_model->discovery($this->_entity));
    }

    private function insertData() {
        return Format::returnData($this->_model->discovery($this->_entity));
    }

    private function getDataById($id) {
        if ($this->_entity_children) {
            $this->_model->setMethod('GET');
            $data = $this->_model->discovery($this->_entity_children, $this->_entity);
        } elseif ($id) {
            $data = $this->_model->discovery($this->_entity);
        }
        return Format::returnData($data, $this->params()->fromQuery('page') ?: 1, $this->_model->getTotalResults());
    }

    private function getAllData() {
        $data = $this->_model->discovery($this->_entity);
        return Format::returnData($data, $this->params()->fromQuery('page') ?: 1, $this->_model->getTotalResults());
    }

    private function getData() {
        $id = $this->params()->fromQuery('id');
        if ($id) {
            $return = $this->getDataById($id);
        } else {
            $return = $this->getAllData();
        }
        return $return;
    }

    public function indexAction() {
        $this->initialize();
        try {
            $return = [];
            switch ($this->_method) {
                case 'DELETE':
                case 'PUT':
                    $return = $this->alterData();
                    break;
                case 'POST':
                    $return = $this->insertData();
                    break;
                case 'GET':
                    $return = ($this->_viewMethod == 'form') ? [] : $this->getData();
                    break;
            }
            switch ($this->_viewMethod) {
                case 'form':
                    $return['response']['form'] = $this->getForm(isset($return['response']['data']['id']) ? $return['response']['data']['id'] : null);
                    break;
            }
            $return['response']['method'] = $this->_method;
            $return['response']['view_method'] = $this->_viewMethod;
        } catch (\Exception $e) {
            $return = array('response' => array('error' => array('code' => $e->getCode(), 'message' => $e->getMessage()), 'success' => false));
        }
        $this->_view->setVariables($return);
        return $this->_view;
    }

}
