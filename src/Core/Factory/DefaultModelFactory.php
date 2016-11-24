<?php

namespace Core\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Core\Model\DefaultModel;

class DefaultModelFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $DefaultModel = new DefaultModel();
        $DefaultModel->initialize($serviceLocator);
        return $DefaultModel;
    }

}
