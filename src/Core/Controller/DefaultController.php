<?php

namespace Core\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class DefaultController extends AbstractActionController {

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $_em;

    public function setEntityManager(\Doctrine\ORM\EntityManager $em) {
        $this->_em = $em;
    }

    /**
     * Return a EntityManager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager() {
        if (null === $this->_em) {
            $this->_em = $this->getServiceLocator()->get('\Doctrine\ORM\EntityManager');
        }
        return $this->_em;
    }

}
