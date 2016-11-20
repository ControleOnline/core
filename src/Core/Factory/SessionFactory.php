<?php

namespace Core\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Core\Storage\SessionStorage;

class SessionFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $conf = $serviceLocator->get('Config');
        $config = null;
        $serviceConfig = null;

        if (isset($conf['session']) && isset($conf['session']['sessionConfig'])) {
            $config = $conf['session']['sessionConfig'];
        }

        if (isset($conf['session']) && isset($conf['session']['serviceConfig'])) {
            $serviceConfig = $conf['session']['serviceConfig'];
        }

        $dbAdapter = $serviceLocator->get('\Zend\Db\Adapter\Adapter');
        return new SessionStorage($dbAdapter, $config, $serviceConfig);
    }

}
