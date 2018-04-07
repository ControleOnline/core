<?php

namespace Core\Model;

use Core\Model\DefaultModel;
use Core\Helper\Format;

class TaxModel extends DefaultModel {

    public function addTaxByWeight($params) {
        if (!$params['company_id']) {
            $this->addError('Company not found!');
            return;
        }
        if (!$params['tax-by-weight']) {
            $this->addError('Tax name is a required field!');
            return;
        }
        if (!$params['tax-price']) {
            $this->addError('Tax price is a required field!');
            return;
        } else {
            $params['tax-price'] = Format::realToCurrency($params['tax-price']);
        }
        if (!$params['final-weight']) {
            $this->addError('Final weight is a required field!');
            return;
        } else {
            $params['final-weight'] = Format::realToCurrency($params['final-weight']);
        }

        $people = $this->_em->getRepository('\Core\Entity\People')->find($params['company_id']);
        if (!$people) {
            $this->addError('Company not found!');
            return;
        }
        $entity = $this->_em->getRepository('\Core\Entity\DeliveryTax')->findOneBy(array(
            'carrier' => $people,
            'tax_name' => $params['tax-by-weight'],
            'final_weight' => $params['final-weight']
        ));
        if (!$entity) {
            return $this->addTax($people, $params['tax-by-weight'], $params['tax-price'], Format::realToCurrency($params['tax-minimum']), $params['tax-optional'], 'fixed', $params['final-weight'], NULL, NULL, NULL, 'kg');
        } else {
            return $entity;
        }
    }

    public function addPercentageTax($params) {
        if (!$params['company_id']) {
            $this->addError('Company not found!');
            return;
        }
        if (!$params['percentage-tax']) {
            $this->addError('Tax name is a required field!');
            return;
        }else{
            $params['percentage-price'] = Format::realToCurrency($params['percentage-price']);
        }
        if (!$params['tax-subtype']) {
            $this->addError('Percentage type is a required field!');
            return;
        }
        if (!$params['tax-price']) {
            $this->addError('Tax price is a required field!');
            return;
        } else {
            $params['tax-price'] = Format::realToCurrency($params['tax-price']);
        }
        $people = $this->_em->getRepository('\Core\Entity\People')->find($params['company_id']);
        if (!$people) {
            $this->addError('Company not found!');
            return;
        }
        $entity = $this->_em->getRepository('\Core\Entity\DeliveryTax')->findOneBy(array(
            'carrier' => $people,
            'tax_name' => $params['fixed-tax']
        ));
        if (!$entity) {
            return $this->addTax($people, $params['percentage-tax'], $params['tax-price'], Format::realToCurrency($params['tax-minimum']), $params['tax-optional'], 'percentage', NULL, NULL, NULL, NULL, $params['tax-subtype']);
        } else {
            return $entity;
        }
    }

    public function addWeightTaxByRegion($params) {
        if (!$params['company_id']) {
            $this->addError('Company not found!');
            return;
        }
        if (!$params['weight-tax-by-region']) {
            $this->addError('Tax name is a required field!');
            return;
        }
        if (!$params['tax-price']) {
            $this->addError('Tax price is a required field!');
            return;
        } else {
            $params['tax-price'] = Format::realToCurrency($params['tax-price']);
        }
        if (!$params['final-weight']) {
            $this->addError('Final weight is a required field!');
            return;
        } else {
            $params['final-weight'] = Format::realToCurrency($params['final-weight']);
        }
        $people = $this->_em->getRepository('\Core\Entity\People')->find($params['company_id']);
        if (!$people) {
            $this->addError('Company not found!');
            return;
        }
        if (!$params['region-origin']) {
            $this->addError('Region origin is a required field!');
            return;
        }
        if (!$params['region-destination']) {
            $this->addError('Region destination is a required field!');
            return;
        }

        $region_origin = $this->_em->getRepository('\Core\Entity\DeliveryRegion')->find($params['region-origin']);
        $region_destination = $this->_em->getRepository('\Core\Entity\DeliveryRegion')->find($params['region-destination']);

        if (!$region_origin) {
            $this->addError('Region origin not found!');
            return;
        }
        if (!$region_destination) {
            $this->addError('Region destination not found!');
            return;
        }

        $entity = $this->_em->getRepository('\Core\Entity\DeliveryTax')->findOneBy(array(
            'carrier' => $people,
            'tax_name' => $params['weight-tax-by-region'],
            'final_weight' => $params['final-weight'],
            'region_origin' => $region_origin->getRegion(),
            'region_destination' => $region_destination->getRegion()
        ));
        if (!$entity) {
            return $this->addTax($people, $params['weight-tax-by-region'], $params['tax-price'], Format::realToCurrency($params['tax-minimum']), $params['tax-optional'], 'fixed', $params['final-weight'], $region_origin, $region_destination, NULL, 'kg');
        } else {
            return $entity;
        }
    }

    public function addPercentageTaxByRegion($params) {
        if (!$params['company_id']) {
            $this->addError('Company not found!');
            return;
        }
        if (!$params['percentage-tax-by-region']) {
            $this->addError('Tax name is a required field!');
            return;
        }
        if (!$params['tax-subtype']) {
            $this->addError('Percentage type is a required field!');
            return;
        }
        if (!$params['tax-price']) {
            $this->addError('Tax price is a required field!');
            return;
        } else {
            $params['tax-price'] = Format::realToCurrency($params['tax-price']);
        }
        if (!$params['final-weight']) {
            $this->addError('Final weight is a required field!');
            return;
        } else {
            $params['final-weight'] = Format::realToCurrency($params['final-weight']);
        }
        $people = $this->_em->getRepository('\Core\Entity\People')->find($params['company_id']);
        if (!$people) {
            $this->addError('Company not found!');
            return;
        }
        if (!$params['region-origin']) {
            $this->addError('Region origin is a required field!');
            return;
        }
        if (!$params['region-destination']) {
            $this->addError('Region destination is a required field!');
            return;
        }

        $region_origin = $this->_em->getRepository('\Core\Entity\DeliveryRegion')->find($params['region-origin']);
        $region_destination = $this->_em->getRepository('\Core\Entity\DeliveryRegion')->find($params['region-destination']);

        if (!$region_origin) {
            $this->addError('Region origin not found!');
            return;
        }
        if (!$region_destination) {
            $this->addError('Region destination not found!');
            return;
        }

        $entity = $this->_em->getRepository('\Core\Entity\DeliveryTax')->findOneBy(array(
            'carrier' => $people,
            'tax_name' => $params['percentage-tax-by-region'],
            'final_weight' => $params['final-weight'],
            'region_origin' => $region_origin->getRegion(),
            'region_destination' => $region_destination->getRegion()
        ));
        if (!$entity) {
            return $this->addTax($people, $params['percentage-tax-by-region'], $params['tax-price'], Format::realToCurrency($params['tax-minimum']), $params['tax-optional'], 'percentage', $params['final-weight'], $region_origin, $region_destination, NULL, $params['tax-subtype']);
        } else {
            return $entity;
        }
    }

    public function addTaxByRegion($params) {
        if (!$params['company_id']) {
            $this->addError('Company not found!');
            return;
        }
        if (!$params['tax-by-region']) {
            $this->addError('Tax name is a required field!');
            return;
        }
        if (!$params['tax-price']) {
            $this->addError('Tax price is a required field!');
            return;
        } else {
            $params['tax-price'] = Format::realToCurrency($params['tax-price']);
        }
        if (!$params['final-weight']) {
            $this->addError('Final weight is a required field!');
            return;
        } else {
            $params['final-weight'] = Format::realToCurrency($params['final-weight']);
        }
        $people = $this->_em->getRepository('\Core\Entity\People')->find($params['company_id']);
        if (!$people) {
            $this->addError('Company not found!');
            return;
        }
        if (!$params['region-origin']) {
            $this->addError('Region origin is a required field!');
            return;
        }
        if (!$params['region-destination']) {
            $this->addError('Region destination is a required field!');
            return;
        }

        $region_origin = $this->_em->getRepository('\Core\Entity\DeliveryRegion')->find($params['region-origin']);
        $region_destination = $this->_em->getRepository('\Core\Entity\DeliveryRegion')->find($params['region-destination']);

        if (!$region_origin) {
            $this->addError('Region origin not found!');
            return;
        }
        if (!$region_destination) {
            $this->addError('Region destination not found!');
            return;
        }

        $entity = $this->_em->getRepository('\Core\Entity\DeliveryTax')->findOneBy(array(
            'carrier' => $people,
            'tax_name' => $params['tax-by-region'],
            'final_weight' => $params['final-weight'],
            'region_origin' => $region_origin->getRegion(),
            'region_destination' => $region_destination->getRegion()
        ));
        if (!$entity) {
            return $this->addTax($people, $params['tax-by-region'], $params['tax-price'], Format::realToCurrency($params['tax-minimum']), $params['tax-optional'], 'fixed', $params['final-weight'], $region_origin, $region_destination);
        } else {
            return $entity;
        }
    }

    public function addFixedTax($params) {
        if (!$params['company_id']) {
            $this->addError('Company not found!');
            return;
        }
        if (!$params['fixed-tax']) {
            $this->addError('Tax name is a required field!');
            return;
        }
        if (!$params['tax-price']) {
            $this->addError('Tax price is a required field!');
            return;
        } else {
            $params['tax-price'] = Format::realToCurrency($params['tax-price']);
        }
        $people = $this->_em->getRepository('\Core\Entity\People')->find($params['company_id']);
        if (!$people) {
            $this->addError('Company not found!');
            return;
        }
        $entity = $this->_em->getRepository('\Core\Entity\DeliveryTax')->findOneBy(array(
            'carrier' => $people,
            'tax_name' => $params['fixed-tax']
        ));
        if (!$entity) {
            return $this->addTax($people, $params['fixed-tax'], $params['tax-price'], Format::realToCurrency($params['tax-minimum']), $params['tax-optional']);
        } else {
            return $entity;
        }
    }

    public function copyTaxByRegion($params) {
        $carrierModel = new \Carrier\Model\CarrierModel();
        $carrierModel->initialize($this->serviceLocator);
        $percentageTaxByRegion = $carrierModel->getFixedTaxByRegion($params['company_id'], $params);
        foreach ($percentageTaxByRegion AS $tax) {
            $region_origin = $this->_em->getRepository('\Core\Entity\DeliveryRegion')->find($params['to-region-origin']);
            $region_destination = $this->_em->getRepository('\Core\Entity\DeliveryRegion')->find($params['to-region-destination']);
            $this->addTax($tax->getCarrier(), $tax->getTaxName(), $tax->getPrice(), $tax->getMinimumPrice(), $tax->getOptional(), $tax->getTaxType(), $tax->getFinalWeight(), $region_origin, $region_destination, $tax->getPeople(), $tax->getTaxSubtype(), $tax->getTaxOrder());
        }
    }

    public function copyPercentageTaxByRegion($params) {
        $carrierModel = new \Carrier\Model\CarrierModel();
        $carrierModel->initialize($this->serviceLocator);
        $percentageTaxByRegion = $carrierModel->getPercentageTaxByRegion($params['company_id'], $params);
        foreach ($percentageTaxByRegion AS $tax) {
            $region_origin = $this->_em->getRepository('\Core\Entity\DeliveryRegion')->find($params['to-region-origin']);
            $region_destination = $this->_em->getRepository('\Core\Entity\DeliveryRegion')->find($params['to-region-destination']);
            $this->addTax($tax->getCarrier(), $tax->getTaxName(), $tax->getPrice(), $tax->getMinimumPrice(), $tax->getOptional(), $tax->getTaxType(), $tax->getFinalWeight(), $region_origin, $region_destination, $tax->getPeople(), $tax->getTaxSubtype(), $tax->getTaxOrder());
        }
    }

    protected function addTax(\Core\Entity\People $carrier, $tax_name, $price, $minimum_price = 0, $optional = false, $tax_type = 'fixed', $final_weight = null, $region_origin = null, $region_destination = null, $people = null, $tax_subtype = NULL, $tax_order = 0) {
        $entity = new \Core\Entity\DeliveryTax();
        $entity->setCarrier($carrier);
        $entity->setFinalWeight($final_weight);
        $entity->setMinimumPrice($minimum_price);
        $entity->setOptional($optional ? 1 : 0);
        $entity->setPeople($people);
        $entity->setPrice($price);
        $entity->setRegionDestination($region_destination);
        $entity->setRegionOrigin($region_origin);
        $entity->setTaxName($tax_name);
        $entity->setTaxOrder($tax_order);
        $entity->setTaxType($tax_type);
        $entity->setTaxSubtype($tax_subtype);
        $this->_em->persist($entity);
        $this->_em->flush($entity);
        return $entity;
    }

    public function deleteTax($id) {
        if ($this->getErrors()) {
            return;
        }
        if (!$id) {
            $this->addError('Address id not informed!');
        } else {
            $entity = $this->_em->getRepository('\Core\Entity\DeliveryTax')->find($id);
            if ($entity) {
                $this->_em->remove($entity);
                $this->_em->flush($entity);
                return true;
            } else {
                $this->addError('Error removing this Tax!');
                return false;
            }
        }
    }

    public function deleteRegion($id) {
        if ($this->getErrors()) {
            return;
        }
        if (!$id) {
            $this->addError('Region id not informed!');
        } else {
            $entity = $this->_em->getRepository('\Core\Entity\DeliveryRegion')->find($id);
            if ($entity) {
                $this->_em->remove($entity);
                $this->_em->flush($entity);
                return true;
            } else {
                $this->addError('Error removing this Region!');
                return false;
            }
        }
    }

}
