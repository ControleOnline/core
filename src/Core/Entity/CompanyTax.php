<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CompanyTax
 *
 * @ORM\Table(name="tax", indexes={@ORM\Index(name="IDX_state_destination_id", columns={"state_destination_id"}),@ORM\Index(name="IDX_state_origin_id", columns={"state_origin_id"}),@ORM\Index(name="IDX_people_id", columns={"people_id"}),@ORM\Index(name="IDX_people_id", columns={"people_id"})})
 * @ORM\Entity
 */
class CompanyTax {

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
     * @var \Core\Entity\State
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\State")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="state_origin_id", referencedColumnName="id")
     * })
     */
    private $state_origin;

    /**
     * @var \Core\Entity\State
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\State")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="state_destination_id", referencedColumnName="id")
     * })
     */
    private $state_destination;

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
     * @return CompanyTax
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
     * @return CompanyTax
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
     * @return CompanyTax
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
     * Set people
     *
     * @param \Core\Entity\People $people
     * @return CompanyTax
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
     * @return CompanyTax
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
     * Set state_origin
     *
     * @param \Core\Entity\State $state_origin
     * @return CompanyTax
     */
    public function setStateOrigin(\Core\Entity\State $state_origin = null) {
        $this->state_origin = $state_origin;

        return $this;
    }

    /**
     * Get state_origin
     *
     * @return \Core\Entity\State 
     */
    public function getStateOrigin() {
        return $this->state_origin;
    }

    /**
     * Set state_destination
     *
     * @param \Core\Entity\State $state_destination
     * @return CompanyTax
     */
    public function setStateDestination(\Core\Entity\State $state_destination = null) {
        $this->state_destination = $state_destination;

        return $this;
    }

    /**
     * Get state_destination
     *
     * @return \Core\Entity\State 
     */
    public function getStateDestination() {
        return $this->state_destination;
    }

    /**
     * Set tax_order
     *
     * @param string $tax_order
     * @return CompanyTax
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
     * @return CompanyTax
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
     * @return CompanyTax
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
