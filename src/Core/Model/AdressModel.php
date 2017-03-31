<?php

namespace Core\Model;

use Core\Model\DefaultModel;

class AdressModel extends DefaultModel {

    public function addPeopleAdress(\Core\Entity\People $people, array $params) {
        if ($this->checkAdressData($params)) {
            $adress = new \Core\Entity\Adress();
            $adress->setComplement($params['complement']);
            $adress->setNickname($params['adress-nickname']);
            $adress->setNumber($params['adress-number']);
            $adress->setPeople($people);
            $cep = $this->discoveryCep($params['cep']);
            $adress->setStreet();
        }
    }

    protected function discoveryAdress() {
        
    }

    protected function discoveryCep(array $params) {
        $entity = $this->_em->getRepository('\Core\Entity\Cep')->findOneBy(array(
            'cep' => $params['cep']
        ));
        if (!$entity) {
            $entity = new \Core\Entity\Cep();
            $entity->setCep($params['cep']);
            $this->_em->persist($entity);
            $this->_em->flush();
            $this->_em->clear();
        }
        return $entity;
    }

    public function checkAdressData($params) {
        $return = true;
        if (!$params['adress-nickname']) {
            $this->addError('Alias off adress is required!');
            $return = false;
        }
        if (!$params['cep']) {
            $this->addError('Company cep is required!');
            $return = false;
        }
        if (!$params['adress']) {
            $this->addError('Company adress is required!');
            $return = false;
        }
        if (!$params['adress-number']) {
            $this->addError('Adress number is required!');
            $return = false;
        }
        if (!$params['neighborhood']) {
            $this->addError('Adress neighborhood name is required!');
            $return = false;
        }
        if (!$params['city']) {
            $this->addError('Adress city is required!');
            $return = false;
        }
        if (!$params['state']) {
            $this->addError('Adress state is required!');
            $return = false;
        }
        if (!$params['country']) {
            $this->addError('Adress country is required!');
            $return = false;
        }
        return $return;
    }

}
