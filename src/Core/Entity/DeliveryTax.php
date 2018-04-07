<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeliveryTax
 *
 * @ORM\Table(name="delivery_tax", indexes={@ORM\Index(name="IDX_region_destination_id", columns={"region_destination_id"}),@ORM\Index(name="IDX_region_origin_id", columns={"region_origin_id"}),@ORM\Index(name="IDX_people_id", columns={"people_id"}),@ORM\Index(name="IDX_carrier_id", columns={"carrier_id"})})
 * @ORM\Entity
 */
class DeliveryTax {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="tax_name", type="string", length=255, nullable=false)
     */
    private $tax_name;

    /**
     * @var string
     *
     * @ORM\Column(name="tax_type", type="string", length=50, nullable=false)
     */
    private $tax_type;

    /**
     * @var string
     *
     * @ORM\Column(name="tax_subtype", type="string", length=50, nullable=true)
     */
    private $tax_subtype;

    /**
     * @var \Core\Entity\People
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\People")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="carrier_id", referencedColumnName="id")
     * })
     */
    private $carrier;

    /**
     * @var \Core\Entity\People
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\People")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="people_id", referencedColumnName="id")
     * })
     */
    private $people;

    /**
     * @var float
     *
     * @ORM\Column(name="minimum_price", type="float",  nullable=true)
     */
    private $minimum_price;

    /**
     * @var float
     *
     * @ORM\Column(name="final_weight", type="float",  nullable=true)
     */
    private $final_weight;

    /**
     * @var \Core\Entity\DeliveryRegion
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\DeliveryRegion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region_origin_id", referencedColumnName="id")
     * })
     */
    private $region_origin;

    /**
     * @var \Core\Entity\DeliveryRegion
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\DeliveryRegion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region_destination_id", referencedColumnName="id")
     * })
     */
    private $region_destination;

    /**
     * @var integer
     *
     * @ORM\Column(name="tax_order", type="integer",  nullable=false)
     */
    private $tax_order;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float",  nullable=true)
     */
    private $price;

    /**
     * @var boolean
     *
     * @ORM\Column(name="optional", type="boolean",  nullable=false)
     */
    private $optional;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set tax_name
     *
     * @param string $tax_name
     * @return DeliveryTax
     */
    public function setTaxName($tax_name) {
        $this->tax_name = $tax_name;

        return $this;
    }

    /**
     * Get tax_name
     *
     * @return string 
     */
    public function getTaxName() {
        return $this->tax_name;
    }

    /**
     * Set tax_type
     *
     * @param string $tax_type
     * @return DeliveryTax
     */
    public function setTaxType($tax_type) {
        $this->tax_type = $tax_type;

        return $this;
    }

    /**
     * Get tax_type
     *
     * @return string 
     */
    public function getTaxType() {
        return $this->tax_type;
    }

    /**
     * Set tax_subtype
     *
     * @param string $tax_subtype
     * @return DeliveryTax
     */
    public function setTaxSubtype($tax_subtype) {
        $this->tax_subtype = $tax_subtype;

        return $this;
    }

    /**
     * Get tax_subtype
     *
     * @return string 
     */
    public function getTaxSubtype() {
        return $this->tax_subtype;
    }

    /**
     * Set carrier
     *
     * @param \Core\Entity\People $people
     * @return DeliveryTax
     */
    public function setCarrier(\Core\Entity\People $people = null) {
        $this->carrier = $people;

        return $this;
    }

    /**
     * Get carrier
     *
     * @return \Core\Entity\People 
     */
    public function getCarrier() {
        return $this->carrier;
    }

    /**
     * Set people
     *
     * @param \Core\Entity\People $people
     * @return DeliveryTax
     */
    public function setPeople(\Core\Entity\People $people = null) {
        $this->people = $people;

        return $this;
    }

    /**
     * Get people
     *
     * @return \Core\Entity\People
     */
    public function getPeople() {
        return $this->people;
    }

    /**
     * Set minimum_price
     *
     * @param string $minimum_price
     * @return DeliveryTax
     */
    public function setMinimumPrice($minimum_price) {
        $this->minimum_price = $minimum_price;

        return $this;
    }

    /**
     * Get minimum_price
     *
     * @return float
     */
    public function getMinimumPrice() {
        return $this->minimum_price;
    }

    /**
     * Set final_weight
     *
     * @param string $final_weight
     * @return DeliveryTax
     */
    public function setFinalWeight($final_weight) {
        $this->final_weight = $final_weight;

        return $this;
    }

    /**
     * Get final_weight
     *
     * @return float
     */
    public function getFinalWeight() {
        return $this->final_weight;
    }

    /**
     * Set region_origin
     *
     * @param \Core\Entity\DeliveryRegion $region_origin
     * @return DeliveryTax
     */
    public function setRegionOrigin(\Core\Entity\DeliveryRegion $region_origin = null) {
        $this->region_origin = $region_origin;

        return $this;
    }

    /**
     * Get region_origin
     *
     * @return \Core\Entity\DeliveryRegion 
     */
    public function getRegionOrigin() {
        return $this->region_origin;
    }

    /**
     * Set region_destination
     *
     * @param \Core\Entity\DeliveryRegion $region_destination
     * @return DeliveryTax
     */
    public function setRegionDestination(\Core\Entity\DeliveryRegion $region_destination = null) {
        $this->region_destination = $region_destination;

        return $this;
    }

    /**
     * Get region_destination
     *
     * @return \Core\Entity\DeliveryRegion 
     */
    public function getRegionDestination() {
        return $this->region_destination;
    }

    /**
     * Set tax_order
     *
     * @param string $tax_order
     * @return DeliveryTax
     */
    public function setTaxOrder($tax_order) {
        $this->tax_order = $tax_order;

        return $this;
    }

    /**
     * Get tax_order
     *
     * @return integer 
     */
    public function getTaxOrder() {
        return $this->tax_order;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return DeliveryTax
     */
    public function setPrice($price) {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * Set optional
     *
     * @param boolean $optional
     * @return DeliveryTax
     */
    public function setOptional($optional) {
        $this->optional = $optional;

        return $this;
    }

    /**
     * Get optional
     *
     * @return boolean 
     */
    public function getOptional() {
        return $this->optional;
    }

}
