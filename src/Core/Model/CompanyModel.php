<?php

namespace Core\Model;

use Core\Model\DefaultModel;
use Zend\Session\Container;
use User\Model\UserModel;
use Core\Model\AddressModel;
use Core\Interfaces\CompanyModelInterface;
use Core\Helper\Format;
use Core\Helper\Url;
use Doctrine\ORM\Query\ResultSetMapping;

abstract class CompanyModel extends DefaultModel implements CompanyModelInterface {

    /**
     * @var \Zend\Session\Container
     */
    protected $_session;

    /**
     * @var \Core\Model\AddressModel
     */
    protected $_addressModel;

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
        $this->_addressModel = new AddressModel();
        $this->_addressModel->initialize($serviceLocator);
        $this->_userModel = new UserModel();
        $this->_userModel->initialize($serviceLocator);
        parent::initialize($serviceLocator);
    }

    public function setCompanyId($company_id) {
        $this->_company_id = $company_id;
        return $this;
    }

    /**
     * @return \Core\Entity\Address
     */
    public function addCompanyAddress(array $params) {
        if ($this->getErrors()) {
            return;
        }
        if ($this->_addressModel->checkAddressData($params)) {
            try {
                $entity_address = $this->_addressModel->addPeopleAddress($this->getCurrentPeopleCompany(), $params);
            } catch (Exception $e) {
                $this->addError(array('code' => $e->getCode(), 'message' => 'Error on create a new address'));
                $this->addError(array('code' => $e->getCode(), 'message' => $e->getMessage()));
                $this->_em->rollback();
            }
            return $entity_address;
        }
    }

    public function getCompanyTax($company_id) {
        $company = $this->_em->getRepository('\Core\Entity\People')->find($company_id);
        if (!$company) {
            ErrorModel::addError('Company not found');
            return;
        }
        return $this->_em->getRepository('\Core\Entity\CompanyTax')->findBy(array('people' => $company));
    }

    public function deleteCompanyTax($id) {
        if ($this->getErrors()) {
            return;
        }
        if (!$id) {
            $this->addError('Address id not informed!');
        } else {
            $entity = $this->_em->getRepository('\Core\Entity\CompanyTax')->find($id);
            if ($entity) {
                $this->_em->remove($entity);
                $this->_em->flush($entity);
                return true;
            } else {
                $this->addError('Error removing this Tax!');
                return false;
            }
        }
    }

    public function addTax($params) {

        $stateOrigin = $this->_em->getRepository('\Core\Entity\State')->find($params['state-origin']);
        $stateDestination = $this->_em->getRepository('\Core\Entity\State')->find($params['state-destination']);
        $company = $this->_em->getRepository('\Core\Entity\People')->find($params['company_id']);

        if (!$stateOrigin) {
            ErrorModel::addError('State origin not found');
            return;
        }

        if (!$stateDestination) {
            ErrorModel::addError('State destination not found');
            return;
        }

        if (!$company) {
            ErrorModel::addError('Company not found');
            return;
        }

        $companyTax = $this->_em->getRepository('\Core\Entity\CompanyTax')->findOneBy(array(
            'tax_type' => 'percentage',
            'tax_subtype' => 'order',
            'tax_name' => $params['tax-name'],
            'people' => $company,
            'state_destination' => $stateDestination,
            'state_origin' => $stateOrigin
        ));
        if (!$companyTax) {
            $companyTax = new \Core\Entity\CompanyTax();
            $companyTax->setTaxType('percentage');
            $companyTax->setTaxSubtype('order');
            $companyTax->setTaxName($params['tax-name']);
            $companyTax->setPeople($company);
            $companyTax->setStateDestination($stateDestination);
            $companyTax->setStateOrigin($stateOrigin);
        }

        $companyTax->setPrice($params['price']);
        $companyTax->setMinimumPrice(0);
        $companyTax->setTaxOrder(0);
        $companyTax->setOptional(0);
        $this->_em->persist($companyTax);
        $this->_em->flush($companyTax);
        return $companyTax;
    }

    public function findByDistance($params, $distance = 600, $limit = 50) {

        $module = 'salesman';

        $sql = "SELECT distance,            
            ddd.ddd AS ddd,
            phone.phone AS phone,
            email.email AS email,
            COALESCE(image.url, '/assets/img/default/profile.png') AS image,
            people.* FROM (
                SELECT people_id, ROUND((SQRT( POW(69.1 * (latitude - (?)), 2) + POW(69.1 * ((?) - longitude) * COS(latitude / 57.3), 2) ) * 1.609),2) AS distance FROM address
            ) AS address_distance                                    
            INNER JOIN people_" . $module . " ON (address_distance.people_id = people_" . $module . "." . $module . "_id)
            INNER JOIN people ON (people_" . $module . "." . $module . "_id = people.id)
            LEFT JOIN phone ON (phone.id = 
                    ( SELECT p.id
                        FROM phone p
                        WHERE p.people_id = people.id                       
                        LIMIT 1
               ) AND people.id = phone.people_id
            )
            LEFT JOIN phone  AS ddd ON (phone.id = ddd.id)
            LEFT JOIN email ON (email.id = 
                    ( SELECT e.id
                        FROM email e
                        WHERE e.people_id = people.id                       
                        LIMIT 1
               ) AND people.id = email.people_id
            )
            LEFT JOIN image ON (people.image_id = image.id)
            HAVING address_distance.distance < ? ORDER BY address_distance.distance LIMIT ?
         ";

        $conn = $this->_em->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->bindValue(1, $params['lat'], \PDO::PARAM_INT);
        $stmt->bindValue(2, $params['lng'], \PDO::PARAM_INT);
        $stmt->bindValue(3, $distance, \PDO::PARAM_INT);
        $stmt->bindValue(4, $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * @return \Core\Entity\People
     */
    public function addCompany(array $params) {
        if ($this->getErrors()) {
            return;
        }
        if ($this->checkData($params) && $this->_addressModel->checkAddressData($params)) {
            try {
                $documentType = $this->discoveryDocumentType('CNPJ', 'J');
                $this->documentExists($params['document-number'], $documentType);
                if (!$this->getErrors()) {
                    $entity_people = new \Core\Entity\People();
                    $entity_people->setName($params['name']);
                    $entity_people->setAlias($params['alias']);
                    $entity_people->setPeopleType('J');
                    $entity_people->setLanguage($this->getDefaultCompany()->getLanguage());

                    $people_document = new \Core\Entity\Document();
                    $people_document->setDocument(Format::onlyNumbers($params['document-number']));
                    $people_document->setDocumentType($this->discoveryDocumentType('CNPJ', 'J'));
                    $people_document->setPeople($entity_people);


                    $this->_em->persist($entity_people);
                    $this->_em->flush($entity_people);
                    $this->addCompanyLink($entity_people, $this->getLoggedPeopleCompany());
                    $this->_em->persist($people_document);
                    $this->_em->flush($people_document);
                    $this->_addressModel->addPeopleAddress($entity_people, $params);
                }
            } catch (Exception $e) {
                $this->addError(array('code' => $e->getCode(), 'message' => 'Error on create a new company'));
                $this->addError(array('code' => $e->getCode(), 'message' => $e->getMessage()));
                $this->_em->rollback();
            }
            return $entity_people;
        }
    }

    public function deleteAddress($id) {
        if ($this->getErrors()) {
            return;
        }
        if (!$this->_userModel->loggedIn()) {
            $this->addError('You do not have permission to delete this!');
        } elseif (!$id) {
            $this->addError('Address id not informed!');
        } elseif (count($this->getCurrentPeopleCompany()->getAddress()) < 2) {
            $this->addError('You need at least one address. Please add another address before removing this one.');
        } else {
            $entity = $this->_em->getRepository('\Core\Entity\Address')->findOneBy(array(
                'id' => $id,
                'people' => $this->getCurrentPeopleCompany()
            ));
            if ($entity) {
                $entity->setPeople(null);
                $this->_em->persist($entity);
                $this->_em->flush($entity);
                return true;
            } else {
                $this->addError('Error removing this Address!');
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
        return $this->_company ? : $this->getDefaultLoggedCompany();
    }

    /**
     * @return \Core\Entity\People
     */
    public function getDefaultLoggedCompany() {
        if ($this->getErrors()) {
            return;
        }
        if ($this->_userModel->loggedIn() && $this->setDefaultLoggedCompany()) {
            $company = $this->setDefaultLoggedCompany();
            $this->_company = $this->_company ? : $company ? $this->_em->getRepository('\Core\Entity\People')->find($company->id) : null;
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
            $company = $this->_userModel->getLoggedUser() && $this->_userModel->getLoggedUser()->getPeople() ? $this->_userModel->getLoggedUser()->getPeople()->getPeopleEmployee() : null;
            if ($company && $company[0]) {
                $this->_session->company = new \stdClass();
                $this->_session->company->id = $company[0]->getCompany()->getId();
                $this->_session->company->name = $company[0]->getCompany()->getName();
                $this->_session->company->alias = $company[0]->getCompany()->getAlias();
            }
        }
        return $this->_session->company;
    }

    public function getPeopleByDocument($document_number) {        
        $people = $this->_em->getRepository('\Core\Entity\People')
                        ->createQueryBuilder('P')
                        ->select()
                        ->innerJoin('\Core\Entity\Document', 'D', 'WITH', 'D.people = P.id')
                        ->where('D.document =:document')
                        ->andWhere('D.documentType=:documentType')
                        ->setParameters(array(
                            'document' => Format::onlyNumbers($document_number),
                            'documentType' => $this->discoveryDocumentType('CNPJ', 'J')
                        ))->getQuery()->getResult();
        return $people ? $people[0] : NULL;
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
            $this->_em->flush($people_document_type);
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
            $entity->setDocument(Format::onlyNumbers($document));
            $entity->setDocumentType($documentType);
            $this->_em->persist($entity);
            $this->_em->flush($entity);
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
                $entity->setLanguage($current_company->getLanguage());
                $this->_em->persist($entity);
                $this->_em->flush($entity);

                $people_company = new \Core\Entity\PeopleEmployee();
                $people_company->setEmployee($entity);
                $people_company->setCompany($current_company);
                $this->_em->persist($people_company);
                $this->_em->flush($people_company);

                foreach ($params['email'] AS $email) {
                    $entity_email = new \Core\Entity\Email();
                    $entity_email->setConfirmed(false);
                    $entity_email->setEmail($email);
                    $entity_email->setPeople($entity);
                    $this->_em->persist($entity_email);
                    $this->_em->flush($entity_email);
                }
                foreach ($params['phone'] AS $phone) {
                    $entity_phone = new \Core\Entity\Phone();
                    $entity_phone->setConfirmed(false);
                    $entity_phone->setDdd(Format::onlyNumbers($phone['ddd']));
                    $entity_phone->setPhone(Format::onlyNumbers($phone['phone']));
                    $entity_phone->setPeople($entity);
                    $this->_em->persist($entity_phone);
                    $this->_em->flush($entity_phone);
                }
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
                return true;
            } else {
                $this->addError('Error removing this Document!');
                return false;
            }
        }
    }

    public function documentExists($document, $document_type) {

        $entity = $this->_em->getRepository('\Core\Entity\Document');
        $doc = $entity->findOneBy(array(
            'document' => Format::onlyNumbers($document),
            'documentType' => $document_type)
        );

        if ($doc) {
            $this->addError(array('message' => 'Document %1$s in use!', 'values' => array('doc' => $document)));
        }

        return $doc;
    }

    abstract function getCurrentPeopleCompany();

    /**
     * @return \Core\Entity\PeopleDomain
     */
    public function getCompanyDomain() {

        $company = $this->getPeopleByDomain() ? : $this->getDefaultCompany();
        foreach ($company->getPeopleDomain() AS $domain) {
            return $domain;
        }
    }

    /**
     * @return \Core\Entity\People
     */
    public function getDefaultCompany() {

        return $this->getPeopleByDomain() ? : $this->_em->getRepository('\Core\Entity\People')->find(1);
    }

    /**
     * @return \Core\Entity\People
     */
    public function getPeopleByDomain() {

        $peopleDomain = $this->_em->getRepository('\Core\Entity\PeopleDomain')->findOneBy(array(
            'domain' => Url::getDomain()
        ));

        return $peopleDomain ? $peopleDomain->getPeople() : false;
    }

}
