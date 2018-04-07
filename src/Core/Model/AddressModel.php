<?php

namespace Core\Model;

use Core\Model\DefaultModel;
use Core\Helper\Format;

class AddressModel extends DefaultModel {

    public function addPeopleAddress(\Core\Entity\People $people, array $params) {
        if ($this->checkAddressData($params)) {
            $cep = $this->discoveryCep(Format::onlyNumbers($params['cep']));
            $country = $this->discoveryCountry($params['country']);
            $state = $this->discoveryState($params, $country);
            $city = $this->discoveryCity($params, $state);
            $district = $this->discoveryDistrict($params, $city);
            $street = $this->discoveryStreet($params, $district, $cep);
            return $this->discoveryAddress($people, $street, $params);
        }
    }

    public function getCitiesByState($state) {
        return $this->_em->getRepository('\Core\Entity\City')->findBy(array(
                    'state' => $state
                        ), array('city' => 'ASC'));
    }

    public function getDefaultCountry() {
        return $this->_em->getRepository('\Core\Entity\Country')->find(31);
    }

    public function getStatesByCountry($country) {
        return $this->_em->getRepository('\Core\Entity\State')->findBy(array(
                    'country' => $country
                        ), array('uf' => 'ASC'));
    }

    protected function discoveryAddress($people, $street, $params) {
        if (!$street) {
            $this->addError('Address street not found!');
            return;
        }
        $entity = $this->_em->getRepository('\Core\Entity\Address')->findOneBy(array(
            'people' => $people,
            'number' => Format::onlyNumbers($params['address-number']),
            'street' => $street,
            'complement' => $params['complement']
        ));
        if (!$entity) {
            $entity = new \Core\Entity\Address();
            $entity->setComplement($params['complement']);
            $entity->setNickname($params['address-nickname']);
            $entity->setNumber(Format::onlyNumbers($params['address-number']));
            $entity->setPeople($people);
            $entity->setStreet($street);
            $entity->setLatitude($params['lat']);
            $entity->setLongitude($params['lng']);
            $this->_em->persist($entity);
            $this->_em->flush($entity);
        }

        return $entity;
    }

    public function addArea($params) {

        if (!$params['company_id']) {
            $this->addError('Company not found!');
            return;
        }
        if (!$params['area']) {
            $this->addError('Area is a required field!');
            return;
        }
        if (!$params['deadline']) {
            $this->addError('Deadline is a required field!');
            return;
        }

        $people = $this->_em->getRepository('\Core\Entity\People')->find($params['company_id']);
        if (!$people) {
            $this->addError('Company not found!');
            return;
        }

        if (!$params['cities']) {
            $this->addError('City is a required field!');
            return;
        }
        $entity = $this->_em->getRepository('\Core\Entity\DeliveryRegion')->findOneBy(array(
            'region' => $params['area'],
            'people' => $people
        ));
        if (!$entity) {
            $entity = new \Core\Entity\DeliveryRegion();
            $entity->setRegion($params['area']);
            $entity->setPeople($people);
            $entity->setDeadline($params['deadline']);
            $entity->setRetrieveTax($params['retrieve-tax']);
            $this->_em->persist($entity);
            $this->_em->flush($entity);
        }

        $state = $this->_em->getRepository('\Core\Entity\State')->find($params['state']);
        $cities = explode(',', $params['cities']);

        foreach ($cities AS $city) {
            if (!empty(trim($city))) {
                $p['city'] = $city;
                $c = $this->discoveryCity($p, $state);

                $deliveryRegionCity = $this->_em->getRepository('\Core\Entity\DeliveryRegionCity')->findOneBy(array(
                    'region' => $entity,
                    'city' => $c
                ));
                if (!$deliveryRegionCity) {
                    $deliveryRegionCity = new \Core\Entity\DeliveryRegionCity();
                    $deliveryRegionCity->setCity($c);
                    $deliveryRegionCity->setRegion($entity);
                    $this->_em->persist($deliveryRegionCity);
                    $this->_em->flush($deliveryRegionCity);
                }
            }
        }

        return $entity;
    }

    protected function discoveryStreet(array $params, $district, $cep) {
        $entity = $this->_em->getRepository('\Core\Entity\Street')->findOneBy(array(
            'street' => $params['street'],
            'district' => $district
        ));
        if (!$entity) {
            $entity = new \Core\Entity\Street();
            $entity->setCep($cep);
            $entity->setDistrict($district);
            $entity->setStreet($params['street']);
            $this->_em->persist($entity);
            $this->_em->flush($entity);
        }
        return $entity;
    }

    protected function discoveryDistrict(array $params, $city) {
        $entity = $this->_em->getRepository('\Core\Entity\District')->findOneBy(array(
            'district' => $params['district'],
            'city' => $city
        ));
        if (!$entity) {
            $entity = new \Core\Entity\District();
            $entity->setCity($city);
            $entity->setDistrict($params['district']);
            $this->_em->persist($entity);
            $this->_em->flush($entity);
        }
        return $entity;
    }

    protected function discoveryCity(array $params, $state) {
        $entity = $this->_em->getRepository('\Core\Entity\City')->findOneBy(array(
            'city' => trim($params['city']),
            'state' => $state
        ));
        if (!$entity) {
            $entity = new \Core\Entity\City();
            $entity->setState($state);
            $entity->setCity(trim($params['city']));
            $this->_em->persist($entity);
            $this->_em->flush($entity);
        }
        return $entity;
    }

    protected function discoveryState($params, $country) {
        if (!$country) {
            $this->addError('Address country not found!');
            return;
        }

        $entity = $this->_em->getRepository('\Core\Entity\State')->findOneBy(array(
                    'uf' => $params['state'],
                    'country' => $country
                ))? : $this->_em->getRepository('\Core\Entity\State')->findOneBy(array(
                    'state' => $params['state'],
                    'country' => $country
        ));

        if (!$entity) {
            if (!$params['state-name']) {
                $this->addError('Address state is required!');
                return;
            }
            $entity = new \Core\Entity\State();
            $entity->setCountry($country);
            $entity->setUf($params['state']);
            $entity->setState($params['state-name']);
            $this->_em->persist($entity);
            $this->_em->flush($entity);
        }
        return $entity;
    }

    protected function discoveryCountry($countryName) {
        return $this->_em->getRepository('\Core\Entity\Country')->findOneBy(array(
                    'countryname' => $countryName
                ))? : $this->_em->getRepository('\Core\Entity\Country')->findOneBy(array(
                    'countrycode' => 'BR'
        ));
    }

    protected function discoveryCep($cep) {
        $entity = $this->_em->getRepository('\Core\Entity\Cep')->findOneBy(array(
            'cep' => Format::onlyNumbers($cep)
        ));
        if (!$entity) {
            $entity = new \Core\Entity\Cep();
            $entity->setCep(Format::onlyNumbers($cep));
            $this->_em->persist($entity);
            $this->_em->flush($entity);
        }
        return $entity;
    }

    public function checkAddressData($params) {
        $return = true;
        if (!$params['address-nickname']) {
            $this->addError('Alias off address is required!');
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
        if (!$params['address-number']) {
            $this->addError('Address number is required!');
            $return = false;
        }
        if (!$params['district']) {
            $this->addError('Address district name is required!');
            $return = false;
        }
        if (!$params['city']) {
            $this->addError('Address city is required!');
            $return = false;
        }
        if (!$params['state']) {
            $this->addError('Address state is required!');
            $return = false;
        }
        if (!$params['country']) {
            $this->addError('Address country is required!');
            $return = false;
        }
        return $return;
    }

}
