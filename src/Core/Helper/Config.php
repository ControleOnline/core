<?php

namespace Core\Helper;

use Company\Model\CompanyModel;

class Config {

    /**
     * @var \Zend\ServiceManager\ServiceManager $_serviceLocator
     */
    protected static $_serviceLocator;

    /**
     * @var \Doctrine\ORM\EntityManager $_em
     */
    protected static $_em;

    /**
     * @var \Company\Model\CompanyModel $_companyModel
     */
    protected static $_companyModel;
    protected static $_config;

    public function initialize(\Zend\ServiceManager\ServiceManager $serviceLocator) {
        if (!self::$_serviceLocator) {
            self::$_serviceLocator = $serviceLocator;
            self::$_companyModel = new CompanyModel();
            self::$_companyModel->initialize($serviceLocator);
            self::$_em = $serviceLocator->get('\Doctrine\ORM\EntityManager');
        }
    }

    public function config($key) {
        return self::getConfig($key);
    }

    public static function getConfig($key) {
        if (self::$_config[$key]) {
            return self::$_config[$key];
        } else {
            $config = self::$_em->getRepository('\Core\Entity\Config')->findOneBy(array(
                'config_key' => $key,
                'people' => self::$_companyModel->getCompanyDomain()
            ));
            self::$_config[$key] = $config ? $config->getConfigValue() : NULL;
            return self::$_config[$key];
        }
    }

    public static function setConfig($key, $value) {
        $config = new \Core\Entity\Config();
        $config->setConfigKey($key);
        $config->setConfigValue($value);
        $config->setPeople(self::$_companyModel->getLoggedPeopleCompany());
        self::$_em->persist($config);
        self::$_em->flush($config);
        return $config;
    }

}
