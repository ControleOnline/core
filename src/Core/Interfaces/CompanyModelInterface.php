<?php

namespace Core\Interfaces;

interface CompanyModelInterface {

    function initialize(\Zend\ServiceManager\ServiceManager $serviceLocator);

    function addCompanyAddress(array $params);

    function deleteAddress($id);

    function discoveryDocumentType($document_type, $people_type);

    function checkData($params);

    function getDocumentTypeExists($document_type);

    function getDocumentTypes();

    function addCompanyDocument($document, $document_type);

    function checkEmail($email);

    function checkPhone($phone);

    function deleteDocument($id);

    function documentExists($document, $document_type);

    /**
     * @return \Core\Entity\People
     */
    function addContact($params);

    /**
     * @return \Core\Entity\People
     */
    function getCurrentPeopleCompany();

    /**
     * @return \Core\Entity\People
     */
    function getDefaultCompany();

    function getAllCompanies();
    
    /**
     * @return \Core\Entity\People
     */
    function addCompany(array $params);

    function addCompanyLink($entity_people, $currentPeopleCompany);

    function findByDistance($params, $distance = 600, $limit = 50);
}
