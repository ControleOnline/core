<?php

namespace Core\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class AbstractController extends AbstractActionController {

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
        return $this->_em;
    }
}
