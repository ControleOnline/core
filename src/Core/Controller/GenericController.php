<?php

namespace Core\Controller;

use Core\DiscoveryModel;
use Core\Model\ErrorModel;
use Core\Controller\DefaultController;

class GenericController extends DefaultController {

    protected $_allowed_methods = array('GET', 'POST', 'PUT', 'DELETE', 'FORM');
    protected $_viewMethod;
    protected $_method;
    protected $_model;
    protected $_entity_children;
    protected $_entity;

    private function getDataById($id) {
        $return = [];
        $page = $this->params()->fromQuery('page') ?: 1;
        if ($this->_entity_children) {
            $this->_model->setMethod('GET');
            $data = $this->_model->discovery($this->_entity_children, $this->_entity);
            $return = array(
                'data' => $data,
                'count' => isset($data[strtolower($this->_entity)][0][strtolower($this->_entity_children)]) ? count($data[strtolower($this->_entity)][0][strtolower($this->_entity_children)]) : 0,
                'total' => (int) $this->_model->getTotalResults(),
                'page' => (int) $page
            );
        } elseif ($id) {
            $data = $this->_model->discovery($this->_entity);
            $return = array(
                'data' => $data,
                'count' => isset($data[strtolower($this->_entity)]) ? count($data[strtolower($this->_entity)]) : 0,
                'total' => (int) $this->_model->getTotalResults(),
                'page' => (int) $page
            );
        }
        return $return;
    }

    private function getAllData() {
        $page = $this->params()->fromQuery('page') ?: 1;
        $data = $this->_model->discovery($this->_entity);

        $return = array(
            'data' => $data,
            'count' => count($data[strtolower($this->_entity)]),
            'total' => (int) $this->_model->getTotalResults(),
            'page' => (int) $page
        );
        return $return;
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
                    $return['response'] = $this->alterData();
                    break;
                case 'POST':
                    $return['response'] = $this->insertData();
                    break;
                case 'GET':
                    $return['response'] = ($this->_viewMethod == 'form') ? [] : $this->getData();
                    break;
            }
            switch ($this->_viewMethod) {
                case 'form':
                    $return['response']['form'] = $this->getForm(isset($return['response']['data']['id']) ? $return['response']['data']['id'] : null);
                    break;
            }
            $return['response']['method'] = $this->_method;
            $return['response']['view_method'] = $this->_viewMethod;
            $return['response']['success'] = isset($return['success']) ? $return['success'] : true;

            if (ErrorModel::getErrors()) {
                foreach (ErrorModel::getErrors() AS $error) {
                    throw new \Exception($error);
                }
            }
        } catch (\Exception $e) {
            $return = array('response' => array('error' => array('code' => $e->getCode(), 'message' => $e->getMessage()), 'success' => false));
        }
        $this->_view->setVariables($return);
        return $this->_view;
    }

}
