<?php

namespace Core\Controller;

use User\Model\UserModel;
use Core\Helper\Format;
use Core\Model\ErrorModel;

class CompanyController extends \Core\Controller\DefaultController {

    /**
     * @var \Company\Model\CompanyModel
     */
    public $_companyModel;

    /**
     * @var \Company\Model\UserModel
     */
    protected $_userModel;

    /**
     * @var \Core\Entity\People
     */
    protected $_currentCompany;

    public function indexAction() {
        if (ErrorModel::getErrors()) {
            return $this->_view;
        }
        $id = $this->params()->fromQuery($this->_module_name) ?: $this->params()->fromPost('company');
        if ($id) {
            $this->_view->setTemplate('company/default/company.phtml');
        } else {            
            $this->_view->setTemplate('company/default/company-list.phtml');
        }
        return $this->_view;
    }

    public function addCompanyAction() {
        if (ErrorModel::getErrors()) {
            return $this->_view;
        }
        $params = $this->params()->fromPost();
        if (!$this->_userModel->loggedIn()) {
            return \Core\Helper\View::redirectToLogin($this->_renderer, $this->getResponse(), $this->getRequest(), $this->redirect());
        } elseif ($params && $this->_userModel->loggedIn()) {
            $company = $this->_companyModel->addCompany($params);                        
            $this->_view->setVariables(Format::returnData(array(
                        'id' => $company->getId(),
                        'name' => $company->getName(),
                        'alias' => $company->getAlias()
            )));            
        }
        
        $this->_view->setTerminal(true);
        return $this->_view;
    }

    public function initialize() {
        $this->_userModel = new UserModel();
        $this->_userModel->initialize($this->serviceLocator);
        parent::initialize();
        $this->_currentCompany = $this->_companyModel->getCurrentPeopleCompany();
        $this->_view->_currentCompany = $this->_currentCompany;
        $this->checkPermission();
    }

    public function addAdressAction() {
        if (ErrorModel::getErrors()) {
            return $this->_view;
        }
        $params = $this->params()->fromPost();
        if ($params && $this->_userModel->loggedIn()) {
            $new_adress = $this->_companyModel->addCompanyAdress($params);
            $this->_view->setVariables(Format::returnData(array(
                        'id' => $new_adress->getId(),
                        'street' => $new_adress->getStreet()->getStreet(),
                        'number' => $new_adress->getNumber(),
                        'complement' => $new_adress->getComplement(),
                        'nickname' => $new_adress->getNickname(),
                        'neighborhood' => $new_adress->getStreet()->getNeighborhood()->getNeighborhood(),
                        'cep' => $new_adress->getStreet()->getCep()->getCep(),
                        'city' => $new_adress->getStreet()->getNeighborhood()->getCity()->getCity(),
                        'state' => $new_adress->getStreet()->getNeighborhood()->getCity()->getState()->getState(),
                        'country' => $new_adress->getStreet()->getNeighborhood()->getCity()->getState()->getCountry()->getCountryname()
            )));
        }

        $this->_view->setTerminal(true);
        return $this->_view;
    }

    public function deleteAdressAction() {
        if (ErrorModel::getErrors()) {
            return $this->_view;
        }
        $this->_companyModel->deleteAdress($this->params()->fromPost('id'));
        return $this->_view;
    }

    public function deleteDocumentAction() {
        if (ErrorModel::getErrors()) {
            return $this->_view;
        }
        $this->_companyModel->deleteDocument($this->params()->fromPost('id'));
        return $this->_view;
    }

    public function addContactAction() {
        if (ErrorModel::getErrors()) {
            return $this->_view;
        }
        if ($this->params()->fromPost() && $this->_userModel->loggedIn()) {
            $entity = $this->_companyModel->addContact($this->params()->fromPost());
            $this->_view->setVariables(Format::returnData($entity));
        }
        $this->_view->setTerminal(true);
        return $this->_view;
    }

    public function addDocumentAction() {
        if (ErrorModel::getErrors()) {
            return $this->_view;
        }
        $document = $this->params()->fromPost('document');
        $document_type = $this->params()->fromPost('document-type');
        if (!$this->_userModel->loggedIn()) {
            return \Core\Helper\View::redirectToLogin($this->_renderer, $this->getResponse(), $this->getRequest(), $this->redirect());
        } else {
            if ($this->params()->fromPost() && $this->_userModel->loggedIn() && $document && $document_type) {
                $new_document = $this->_companyModel->addCompanyDocument($document, $document_type);
                $this->_view->setVariables(Format::returnData($new_document));
            } elseif ($this->params()->fromPost()) {
                ErrorModel::addError('The document field is required.');
            } else {
                $document_types = $this->_companyModel->getDocumentTypes();
                $this->_view->setVariables(array('document_types' => $document_types));
            }
        }
        $this->_view->setTerminal(true);
        return $this->_view;
    }

}
