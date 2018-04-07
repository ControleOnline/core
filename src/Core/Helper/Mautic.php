<?php

namespace Core\Helper;

use Mautic\Auth\ApiAuth;
use Mautic\MauticApi;
use Zend\Session\Container;
use Core\Model\ErrorModel;
use Core\Helper\Config;

class Mautic {

    /**
     * @var \Zend\Session\Container
     */
    protected static $_session;
    protected static $data;

    protected static function getSession() {
        if (!self::$_session) {
            self::$_session = new Container('mautic');
            self::$_session->mautic = new \stdClass();
        }
        return self::$_session;
    }

    protected static function basicAuth() {
        if (!self::getSession()->mautic->auth) {
            $settings = array(
                'userName' => Config::getConfig('mautic-basic-auth-user'), // Create a new user       
                'password' => Config::getConfig('mautic-basic-auth-password')    // Make it a secure password
            );
            $initAuth = new ApiAuth();
            self::getSession()->mautic->auth = $initAuth->newAuth($settings, 'BasicAuth');
        }
        return self::getSession()->mautic->auth;
    }

    public static function getMauticFormAction($id) {
        return Config::getConfig('mautic-url') . '/form/submit?formId=' . $id;
    }

    public static function getOAuth2SecretKey() {
        return Config::getConfig('mautic-o-auth2-secret-key');
    }

    public static function getOAuth2PublicKey() {
        return Config::getConfig('mautic-o-auth2-public-key');
    }

    public static function addProspect($name, $email, $phone, $tags) {
        self::$data['contact']['email'] = $email;
        self::$data['contact']['firstname'] = $name;
        self::$data['contact']['phone'] = $phone;
        self::$data['contact']['tags'] = $tags;
    }

    public static function addContact(\Core\Entity\People $user, $tag = 'new') {
        self::$data['contact']['firstname'] = $user->getName();
        self::$data['contact']['lastname'] = $user->getAlias();
        self::$data['contact']['email'] = $user->getEmail()[0]->getEmail();
        $user->getPhone() ? self::$data['contact']['phone'] = $user->getPhone()[0]->getDdd() . $user->getPhone()[0]->getPhone() : null;
        self::$data['contact']['tags'] = $tag;
    }

    public static function addDefaultCompany(\Core\Entity\People $default_company) {
        self::$data['default_company']['company'] = $default_company->getAlias();
        self::$data['default_company']['companyname'] = $default_company->getName();
    }

    public static function addCompany(\Core\Entity\People $company) {
        self::$data['company']['company'] = $company->getAlias();
        self::$data['company']['companyname'] = $company->getName();
    }

    public static function persist() {
        self::basicAuth();
        $api = new MauticApi();
        $contactApi = $api->newApi('contacts', self::getSession()->mautic->auth, Config::getConfig('mautic-url'));
        $contactApi->create(array_merge(self::$data['company']? : array(), self::$data['contact']? : array()));
        $contactApi->create(array_merge(self::$data['default_company']? : array(), self::$data['contact']? : array()));
        self::$data = array();
    }

}
