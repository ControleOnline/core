<?php

namespace Core\Storage;

use Zend\Session\SaveHandler\DbTableGateway;
use Zend\Session\SaveHandler\DbTableGatewayOptions;
use Core\SaveHandler\EncodedDbTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Session\SessionManager;
use Zend\Session\Container;

class SessionStorage {

    protected $adapter;
    protected $tblGW;
    protected $sessionConfig;
    protected $serviceConfig;

    public function __construct(Adapter $adapter, $session_config, $service_config) {
        $this->adapter = $adapter;
        $this->sessionConfig = $session_config;
        $this->serviceConfig = $service_config;
        $this->tblGW = new \Zend\Db\TableGateway\TableGateway('sessions', $this->adapter);
    }

    public function setSessionStorage() {
        $gwOpts = new DbTableGatewayOptions();
        $gwOpts->setDataColumn('data');
        $gwOpts->setIdColumn('id');
        $gwOpts->setLifetimeColumn('lifetime');
        $gwOpts->setModifiedColumn('modified');
        $gwOpts->setNameColumn('name');


        if (isset($this->serviceConfig['base64Encode']) &&
                $this->serviceConfig['base64Encode']) {
            $saveHandler = new EncodedDbTableGateway($this->tblGW, $gwOpts);
        } else {
            $saveHandler = new DbTableGateway($this->tblGW, $gwOpts);
        }
        $sessionManager = new SessionManager();
        if ($this->sessionConfig) {
            $sessionConfig = new \Zend\Session\Config\SessionConfig();
            $sessionConfig->setOptions($this->sessionConfig);
            $sessionManager->setConfig($sessionConfig);
        }
        $sessionManager->setSaveHandler($saveHandler);
        Container::setDefaultManager($sessionManager);
        $sessionManager->start();
    }

}
