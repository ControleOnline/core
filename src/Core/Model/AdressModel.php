<?php

namespace Core\Model;

use Core\Model\DefaultModel;

class AdressModel extends DefaultModel {

    public function addPeopleAdress(\Core\Entity\People $people, array $params) {
        if ($this->checkAdressData($params)) {
            $cep = $this->discoveryCep($params['cep']);
            $country = $this->discoveryCountry($params['country-code']);
            $state = $this->discoveryState($params, $country);
            $city = $this->discoveryCity($params, $state);
            $neighborhood = $this->discoveryNeighborhood($params, $city);
            $street = $this->discoveryStreet($params, $neighborhood, $cep);
            return $this->discoveryAdress($people, $street, $params);
        }
    }

    protected function discoveryAdress($people, $street, $params) {
        if (!$street) {
            $this->addError('Adress street not found!');
            return;
        }
        $entity = $this->_em->getRepository('\Core\Entity\Adress')->findOneBy(array(
            'people' => $people,
            'number' => $params['adress-number'],
            'street' => $street,
            'complement' => $params['complement']
        ));
        if (!$entity) {
            $entity = new \Core\Entity\Adress();
            $entity->setComplement($params['complement']);
            $entity->setNickname($params['adress-nickname']);
            $entity->setNumber($params['adress-number']);
            $entity->setPeople($people);
            $entity->setStreet($street);
            $this->_em->persist($entity);
            $this->_em->flush();
            $this->_em->clear();
        }
        return $entity;
    }

    protected function discoveryStreet(array $params, $neighborhood, $cep) {
        $entity = $this->_em->getRepository('\Core\Entity\Street')->findOneBy(array(
            'street' => $params['street'],
            'neighborhood' => $neighborhood
        ));
        if (!$entity) {
            $entity = new \Core\Entity\Street();
            $entity->setCep($cep);
            $entity->setNeighborhood($neighborhood);
            $entity->setStreet($params['street']);
            $this->_em->persist($entity);
            $this->_em->flush();
            $this->_em->clear();
        }
        return $entity;
    }

    protected function discoveryNeighborhood(array $params, $city) {
        $entity = $this->_em->getRepository('\Core\Entity\Neighborhood')->findOneBy(array(
            'neighborhood' => $params['neighborhood'],
            'city' => $city
        ));
        if (!$entity) {
            $entity = new \Core\Entity\Neighborhood();
            $entity->setCity($city);
            $entity->setNeighborhood($params['neighborhood']);
            $this->_em->persist($entity);
            $this->_em->flush();
            $this->_em->clear();
        }
        return $entity;
    }

    protected function discoveryCity(array $params, $state) {
        $entity = $this->_em->getRepository('\Core\Entity\City')->findOneBy(array(
            'city' => $params['city'],
            'state' => $state
        ));
        if (!$entity) {
            $entity = new \Core\Entity\City();
            $entity->setState($state);
            $entity->setCity($params['city']);
            $this->_em->persist($entity);
            $this->_em->flush();
            $this->_em->clear();
        }
        return $entity;
    }

    protected function discoveryState($params, $country) {
        if (!$country) {
            $this->addError('Adress country not found!');
            return;
        }

        $entity = $this->_em->getRepository('\Core\Entity\State')->findOneBy(array(
            'uf' => $params['state'],
            'country' => $country
        ));

        if (!$entity) {
            if (!$params['state-name']) {
                $this->addError('Adress state is required!');
                return;
            }
            $entity = new \Core\Entity\State();
            $entity->setCountry($country);
            $entity->setUf($params['state']);
            $entity->setState($params['state-name']);
            $this->_em->persist($entity);
            $this->_em->flush();
            $this->_em->clear();
        }
        return $entity;
    }

    protected function discoveryCountry($countryCode) {
        return $this->_em->getRepository('\Core\Entity\Country')->findOneBy(array(
                    'countrycode' => $countryCode
        ));
    }

    protected function discoveryCep($cep) {
        $entity = $this->_em->getRepository('\Core\Entity\Cep')->findOneBy(array(
            'cep' => $cep
        ));
        if (!$entity) {
            $entity = new \Core\Entity\Cep();
            $entity->setCep($cep);
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
        if (!$params['street']) {
            $this->addError('Company street is required!');
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
        if (!$params['country'] || !$params['country-code']) {
            $this->addError('Adress country is required!');
            $return = false;
        }
        return $return;
    }

}
