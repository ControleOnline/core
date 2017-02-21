<?php

namespace Core\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Core\Controller\DefaultController;

class DefaultControllerFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {        
        $DefaultController = new DefaultController();
        $DefaultController->setConfig($serviceLocator->get('Config'));
        $DefaultController->setServiceLocator($serviceLocator);
        $DefaultController->setEntityManager($serviceLocator->get('\Doctrine\ORM\EntityManager'));        
        return $DefaultController;
    }

}
