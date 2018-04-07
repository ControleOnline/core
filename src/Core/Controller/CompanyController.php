<?php

namespace Core\Controller;

use User\Model\UserModel;
use Core\Helper\Format;
use Core\Model\ErrorModel;
use Core\Model\AddressModel;

class CompanyController extends \Core\Controller\DefaultController {

    /**
     * @var \Company\Model\CompanyModel
     */
    public $_companyModel;

    /**
     * @var \Core\Entity\People
     */
    protected $_currentCompany;

    public function indexAction() {
        if (ErrorModel::getErrors()) {
            return $this->_view;
        }
        $id = $this->params()->fromQuery($this->module_name) ?: $this->params()->fromPost('company');
        if ($id) {
            $this->_view->company_tax = $this->_companyModel->getCompanyTax($id);
            /**
             * @todo Ajustar pra puxar uma view pra perfil e não essa da empresa
             */
            //$this->_view->setTemplate('company/default/company.phtml');
        } else {
            $this->_view->setTemplate('company/default/company-list.phtml');
        }
        return $this->_view;
    }

    public function deleteCompanyTaxAction() {
        if (ErrorModel::getErrors()) {
            return $this->_view;
        }
        $this->_companyModel->deleteCompanyTax($this->params()->fromPost('id'));
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

    public function findByDistanceAction() {
        if (ErrorModel::getErrors()) {
            return $this->_view;
        }
        $params = $this->params()->fromPost();
        if ($params && isset($params['lat']) && $params['lat'] && isset($params['lng']) && $params['lng']) {
            $this->_view->setTerminal(true);
            return Format::returnData($this->_companyModel->findByDistance($params));
        }
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

    public function addAddressAction() {
        if (ErrorModel::getErrors()) {
            return $this->_view;
        }
        $params = $this->params()->fromPost();
        if ($params && $this->_userModel->loggedIn()) {
            $new_address = $this->_companyModel->addCompanyAddress($params);
            $this->_view->setVariables(Format::returnData(array(
                        'id' => $new_address->getId(),
                        'street' => $new_address->getStreet()->getStreet(),
                        'number' => $new_address->getNumber(),
                        'complement' => $new_address->getComplement(),
                        'nickname' => $new_address->getNickname(),
                        'district' => $new_address->getStreet()->getDistrict()->getDistrict(),
                        'cep' => $new_address->getStreet()->getCep()->getCep(),
                        'city' => $new_address->getStreet()->getDistrict()->getCity()->getCity(),
                        'state' => $new_address->getStreet()->getDistrict()->getCity()->getState()->getState(),
                        'country' => $new_address->getStreet()->getDistrict()->getCity()->getState()->getCountry()->getCountryname()
            )));
        }

        $this->_view->setTerminal(true);
        return $this->_view;
    }

    public function deleteAddressAction() {
        if (ErrorModel::getErrors()) {
            return $this->_view;
        }
        $this->_companyModel->deleteAddress($this->params()->fromPost('id'));
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

    public function addTaxAction() {
        if (ErrorModel::getErrors()) {
            return $this->_view;
        }
        $params = $this->params()->fromPost();

        $addressModel = new AddressModel();
        $addressModel->initialize($this->serviceLocator);
        $country = $addressModel->getDefaultCountry();
        $this->_view->states = $addressModel->getStatesByCountry($country);

        if ($params && $this->_userModel->loggedIn()) {
            $new_tax = $this->_companyModel->addTax($params);
            $this->_view->setVariables(Format::returnData(array(
                        'id' => $new_tax->getId(),
                        'tax' => $new_tax->getTaxName(),
                        'state_origin' => $new_tax->getStateOrigin()->getState(),
                        'state_destination' => $new_tax->getStateDestination()->getState(),
                        'price' => number_format($new_tax->getPrice(), 2, ',', '.') . ' %'
            )));
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
