<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuoteDetail
 *
 * @ORM\Table(name="quote_detail", indexes={@ORM\Index(name="IDX_region_destination_id", columns={"region_destination_id"}),@ORM\Index(name="IDX_region_origin_id", columns={"region_origin_id"}),@ORM\Index(name="IDX_delivery_tax_id", columns={"delivery_tax_id"}),@ORM\Index(name="IDX_quote", columns={"quote_id"})})
 * @ORM\Entity
 */
class QuoteDetail {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Core\Entity\Quote
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Quote")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="quote_id", referencedColumnName="id")
     * })
     */
    private $quote;

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
     * @var \Core\Entity\DeliveryTax
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\DeliveryTax")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="delivery_tax_id", referencedColumnName="id")
     * })
     */
    private $delivery_tax;

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
     * @var float
     *
     * @ORM\Column(name="price_calculated", type="float",  nullable=false)
     */
    private $price_calculated;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set quote
     *
     * @param \Core\Entity\Quote $quote
     * @return Order
     */
    public function setQuote(\Core\Entity\Quote $quote = null) {
        $this->quote = $quote;

        return $this;
    }

    /**
     * Get quote
     *
     * @return \Core\Entity\Quote 
     */
    public function getQuote() {
        return $this->quote;
    }

    /**
     * Set tax_name
     *
     * @param string $tax_name
     * @return QuoteDetail
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
     * @return QuoteDetail
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
     * @return QuoteDetail
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
     * Set delivery_tax
     *
     * @param \Core\Entity\DeliveryTax $delivery_tax
     * @return QuoteDetail
     */
    public function setDeliveryTax(\Core\Entity\DeliveryTax $delivery_tax = null) {
        $this->delivery_tax = $delivery_tax;

        return $this;
    }

    /**
     * Get delivery_tax
     *
     * @return \Core\Entity\DeliveryTax 
     */
    public function getDeliveryTax() {
        return $this->delivery_tax;
    }

    /**
     * Set minimum_price
     *
     * @param string $minimum_price
     * @return QuoteDetail
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
     * @param float $final_weight
     * @return QuoteDetail
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
     * @return QuoteDetail
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
     * @return QuoteDetail
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
     * @param integer $tax_order
     * @return QuoteDetail
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
     * @param float $price
     * @return QuoteDetail
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
     * @return QuoteDetail
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

    /**
     * Set price_calculated
     *
     * @param float $price_calculated
     * @return QuoteDetail
     */
    public function setPriceCalculated($price_calculated) {
        $this->price_calculated = $price_calculated;

        return $this;
    }

    /**
     * Get price_calculated
     *
     * @return float
     */
    public function getPriceCalculated() {
        return $this->price_calculated;
    }

}
