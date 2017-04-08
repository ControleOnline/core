<?php

namespace Core\Model;

use Core\Model\DefaultModel;
use Zend\Session\Container;
use User\Model\UserModel;
use Core\Model\AdressModel;
use Core\Interfaces\CompanyModelInterface;

abstract class CompanyModel extends DefaultModel implements CompanyModelInterface {

    /**
     * @var \Zend\Session\Container
     */
    protected $_session;

    /**
     * @var \Core\Model\AdressModel
     */
    protected $_adressModel;

    /**
     * @var \Core\Entity\People
     */
    protected $_company;

    /**
     * @var \User\Model\UserModel
     */
    protected $_userModel;
    protected $_company_id;

    public function __construct() {
        $this->_session = new Container('company');
    }

    public function initialize(\Zend\ServiceManager\ServiceManager $serviceLocator) {
        $this->_adressModel = new AdressModel();
        $this->_adressModel->initialize($serviceLocator);
        $this->_userModel = new UserModel();
        $this->_userModel->initialize($serviceLocator);
        parent::initialize($serviceLocator);
    }

    public function setCompanyId($company_id) {
        $this->_company_id = $company_id;
        return $this;
    }

    /**
     * @return \Core\Entity\Adress
     */
    public function addCompanyAdress(array $params) {
        if ($this->getErrors()) {
            return;
        }
        if ($this->_adressModel->checkAdressData($params)) {
            try {
                $entity_adress = $this->_adressModel->addPeopleAdress($this->getCurrentPeopleCompany(), $params);
                $this->_em->flush();
                $this->_em->clear();
            } catch (Exception $e) {
                $this->addError(array('code' => $e->getCode(), 'message' => 'Error on create a new adress'));
                $this->addError(array('code' => $e->getCode(), 'message' => $e->getMessage()));
                $this->_em->rollback();
            }
            return $entity_adress;
        }
    }

    /**
     * @return \Core\Entity\People
     */
    public function addCompany(array $params) {
        if ($this->getErrors()) {
            return;
        }
        if ($this->checkData($params) && $this->_adressModel->checkAdressData($params)) {
            try {
                $entity_people = new \Core\Entity\People();
                $entity_people->setName($params['name']);
                $entity_people->setAlias($params['alias']);
                $entity_people->setPeopleType('J');
                $this->_em->persist($entity_people);                                                
                
                $this->addCompanyLink($entity_people, $this->getLoggedPeopleCompany());

                $people_document = new \Core\Entity\Document();
                $people_document->setDocument($params['document-number']);
                $people_document->setDocumentType($this->discoveryDocumentType('CNPJ', 'J'));
                $people_document->setPeople($entity_people);
                $this->_em->persist($people_document);

                $this->_adressModel->addPeopleAdress($entity_people, $params);

                $this->_em->flush();
                $this->_em->clear();
                
            } catch (Exception $e) {
                $this->addError(array('code' => $e->getCode(), 'message' => 'Error on create a new company'));
                $this->addError(array('code' => $e->getCode(), 'message' => $e->getMessage()));
                $this->_em->rollback();
            }
            return $entity_people;
        }
    }

    public function deleteAdress($id) {
        if ($this->getErrors()) {
            return;
        }
        if (!$this->_userModel->loggedIn()) {
            $this->addError('You do not have permission to delete this!');
        } elseif (!$id) {
            $this->addError('Adress id not informed!');
        } elseif (count($this->getCurrentPeopleCompany()->getAdress()) < 2) {
            $this->addError('You need at least one adress. Please add another adress before removing this one.');
        } else {
            $entity = $this->_em->getRepository('\Core\Entity\Adress')->findOneBy(array(
                'id' => $id,
                'people' => $this->getCurrentPeopleCompany()
            ));
            if ($entity) {
                $entity->setPeople(null);
                $this->_em->persist($entity);
                $this->_em->flush();
                $this->_em->clear();
                return true;
            } else {
                $this->addError('Error removing this Adress!');
                return false;
            }
        }
    }

    /**
     * @return \Core\Entity\People
     */
    public function getLoggedPeopleCompany() {
        if ($this->getErrors()) {
            return;
        }
        return $this->_company ?: $this->getDefaultLoggedCompany();
    }

    /**
     * @return \Core\Entity\People
     */
    public function getDefaultLoggedCompany() {
        if ($this->getErrors()) {
            return;
        }
        if ($this->_userModel->loggedIn() && $this->setDefaultLoggedCompany()) {
            $this->_company = $this->_company ?: $this->_em->getRepository('\Core\Entity\People')->find($this->setDefaultLoggedCompany()->id);
        } else {
            unset($this->_session->company);
            $this->_company = null;
        }
        return $this->_company;
    }

    public function setDefaultLoggedCompany() {
        if ($this->getErrors()) {
            return;
        }
        if (!$this->_session->company) {
            $company = $this->_userModel->getLoggedUser()->getPeople()->getPeopleEmployee();
            if ($company && $company[0]) {
                $this->_session->company = new \stdClass();
                $this->_session->company->id = $company[0]->getCompany()->getId();
                $this->_session->company->name = $company[0]->getCompany()->getName();
                $this->_session->company->alias = $company[0]->getCompany()->getAlias();
            }
        }
        return $this->_session->company;
    }

    public function discoveryDocumentType($document_type, $people_type) {
        if ($this->getErrors()) {
            return;
        }
        $people_document_type = $this->_em->getRepository('\Core\Entity\DocumentType')->findOneBy(array(
            'documentType' => $document_type
        ));
        if (!$people_document_type) {
            $people_document_type = new \Core\Entity\DocumentType();
            $people_document_type->setPeopleType($people_type);
            $people_document_type->setDocumentType($document_type);
            $this->_em->persist($people_document_type);
        }
        return $people_document_type;
    }

    public function checkData($params) {
        if ($this->getErrors()) {
            return;
        }
        $return = true;
        if (!$params['document-number']) {
            $this->addError('Document number is required!');
            $return = false;
        } else {
            $entity = $this->_em->getRepository('\Core\Entity\Document')->findOneBy(array(
                'document' => $params['document-number']
            ));
            if ($entity) {
                $this->addError('Document in use!');
                $return = false;
            }
        }
        if (!$params['name']) {
            $this->addError('Company name is required!');
            $return = false;
        }
        if (!$params['alias']) {
            $this->addError('Company alias is required!');
            $return = false;
        }

        return $return;
    }

    public function getDocumentTypeExists($document_type) {
        if ($this->getErrors()) {
            return;
        }
        $entity = $this->_em->getRepository('\Core\Entity\DocumentType');
        $doc = $entity->findOneBy(array(
            'documentType' => $document_type,
            'peopleType' => 'J'
        ));
        if (!$doc) {
            $this->addError('This type of document does not exist');
        }

        return $doc;
    }

    public function getDocumentTypes() {
        if ($this->getErrors()) {
            return;
        }
        $entity = $this->_em->getRepository('\Core\Entity\DocumentType');
        return $entity->findBy(array('peopleType' => 'J'), array('documentType' => 'ASC'), 100);
    }

    public function addCompanyDocument($document, $document_type) {
        if ($this->getErrors()) {
            return;
        }
        $current_company = $this->getCurrentPeopleCompany();
        $documentType = $this->getDocumentTypeExists($document_type);
        $this->documentExists($document, $documentType);
        if (!$this->getErrors()) {
            $entity = new \Core\Entity\Document();
            $entity->setPeople($current_company);
            $entity->setDocument($document);
            $entity->setDocumentType($documentType);
            $this->_em->persist($entity);

            $this->_em->flush();
            $this->_em->clear();
            return array(
                'id' => $entity->getId(),
                'document' => $entity->getDocument(),
                'document_type' => $entity->getDocumentType()->getDocumentType(),
                'image' => $entity->getImage() ? $entity->getImage()->getUrl() : null
            );
        }
    }

    public function checkEmail($email) {
        if ($this->getErrors()) {
            return;
        }
        if (!$email) {
            $this->addError('Email is required!');
        } else {
            foreach ($email AS $check_email) {
                $entity_email = $this->_em->getRepository('\Core\Entity\Email');
                $mail = $entity_email->findOneBy(array('email' => $check_email));
                if ($mail) {
                    $this->addError(array('message' => 'Email %1$s in use!', 'values' => array('user' => $check_email)));
                }
            }
        }
    }

    public function checkPhone($phone) {
        if ($this->getErrors()) {
            return;
        }
        if (!$phone) {
            $this->addError('Phone is required!');
        } else {
            foreach ($phone AS $check_phone) {
                if (!$check_phone['ddd']) {
                    $this->addError('DDD is required!');
                }
                if (!$check_phone['phone']) {
                    $this->addError('Phone is required!');
                }
            }
        }
    }

    /**
     * @return \Core\Entity\People
     */
    public function addContact($params) {
        if ($this->getErrors()) {
            return;
        }
        try {
            $current_company = $this->getCurrentPeopleCompany();
            $this->checkEmail($params['email']);
            $this->checkPhone($params['phone']);
            if (!$params['name']) {
                $this->addError('Name is required!');
            }
            if (!$this->getErrors()) {
                $entity = new \Core\Entity\People();
                $entity->setPeopleType('F');
                $entity->setName($params['name']);
                $entity->setImage(null);
                $entity->setAlias('');
                $this->_em->persist($entity);

                $people_company = new \Core\Entity\PeopleEmployee();
                $people_company->setEmployee($entity);
                $people_company->setCompany($current_company);
                $this->_em->persist($people_company);

                foreach ($params['email'] AS $email) {
                    $entity_email = new \Core\Entity\Email();
                    $entity_email->setConfirmed(false);
                    $entity_email->setEmail($email);
                    $entity_email->setPeople($entity);
                    $this->_em->persist($entity_email);
                }
                foreach ($params['phone'] AS $phone) {
                    $entity_phone = new \Core\Entity\Phone();
                    $entity_phone->setConfirmed(false);
                    $entity_phone->setDdd($phone['ddd']);
                    $entity_phone->setPhone($phone['phone']);
                    $entity_phone->setPeople($entity);
                    $this->_em->persist($entity_phone);
                }

                $this->_em->flush();
                $this->_em->clear();

                return array(
                    'id' => $entity->getId(),
                    'name' => $entity->getName(),
                    'image' => $entity->getImage() ? $entity->getImage()->getUrl() : null,
                    'email' => $entity_email->getEmail(),
                    'ddd' => $entity_phone->getDdd(),
                    'phone' => $entity_phone->getPhone()
                );
            }
        } catch (Exception $e) {
            $this->addError(array('code' => $e->getCode(), 'message' => 'Error on create a new contact'));
            $this->addError(array('code' => $e->getCode(), 'message' => $e->getMessage()));
            $this->_em->rollback();
        }
    }

    public function deleteDocument($id) {
        if ($this->getErrors()) {
            return;
        }
        if (!$this->_userModel->loggedIn()) {
            $this->addError('You do not have permission to delete this!');
        } elseif (!$id) {
            $this->addError('Document id not informed!');
        } else {
            $entity = $this->_em->getRepository('\Core\Entity\Document')->findOneBy(array(
                'id' => $id,
                'people' => $this->getCurrentPeopleCompany()
            ));
            if ($entity) {
                $this->_em->remove($entity);
                $this->_em->flush();
                $this->_em->clear();
                return true;
            } else {
                $this->addError('Error removing this Document!');
                return false;
            }
        }
    }

    public function documentExists($document, $document_type) {
        if ($this->getErrors()) {
            return;
        }
        $entity = $this->_em->getRepository('\Core\Entity\Document');
        $doc = $entity->findOneBy(array('document' => $document));

        $documentType = $entity->findOneBy(array(
            'documentType' => $document_type,
            'people' => $this->getCurrentPeopleCompany()
        ));

        if ($documentType) {
            $this->addError(array('message' => 'Document type (%1$s) already added!', 'values' => array('docType' => $document_type->getDocumentType())));
        }
        if ($doc) {
            $this->addError(array('message' => 'Document %1$s in use!', 'values' => array('doc' => $document)));
        }
        return $doc;
    }

    abstract function getCurrentPeopleCompany();

    /**
     * @return \Core\Entity\People
     */
    public function getDefaultCompany() {
        if ($this->getErrors()) {
            return;
        }
        return $this->_em->getRepository('\Core\Entity\People')->find(1);
    }

}
