<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quote
 *
 * @ORM\Table(name="quote", uniqueConstraints={@ORM\UniqueConstraint(name="client_id", columns={"client_id", "order_id"})}, indexes={@ORM\Index(name="IDX_city_destination_id", columns={"city_destination_id"}),@ORM\Index(name="IDX_city_origin_id", columns={"city_origin_id"}),@ORM\Index(name="IDX_provider_id", columns={"provider_id"}),@ORM\Index(name="IDX_carrier_id", columns={"carrier_id"}),@ORM\Index(name="IDX_client_id", columns={"client_id"}),@ORM\Index(name="IDX_order_id", columns={"order_id"})})
 * @ORM\Entity
 */
class Quote {

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
     * @ORM\Column(name="ip", type="string",  nullable=true)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="internal_ip", type="string",  nullable=true)
     */
    private $internal_ip;

    /**
     * @var integer
     *
     * @ORM\Column(name="deadline", type="integer",  nullable=true)
     */
    private $deadline;

    /**
     * @var boolean
     *
     * @ORM\Column(name="denied", type="boolean",  nullable=true)
     */
    private $denied;

    /**
     * @var \Core\Entity\People
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\People")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * })
     */
    private $client;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\QuoteDetail", mappedBy="quote")
     */
    private $quote_detail;

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
     *   @ORM\JoinColumn(name="provider_id", referencedColumnName="id")
     * })
     */
    private $provider;

    /**
     * @var \Core\Entity\City
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\City")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city_origin_id", referencedColumnName="id")
     * })
     */
    private $city_origin;

    /**
     * @var \Core\Entity\City
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\City")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city_destination_id", referencedColumnName="id")
     * })
     */
    private $city_destination;

    /**
     * @var \Core\Entity\Order
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Order")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     * })
     */
    private $order;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float",  nullable=true)
     */
    private $total;

    public function __construct() {
        $this->quote_detail = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Add quote_detail
     *
     * @param \Core\Entity\QuoteDetail $quote_detail
     * @return Quote
     */
    public function addQuoteDetail(\Core\Entity\QuoteDetail $quote_detail) {
        $this->quote_detail[] = $quote_detail;

        return $this;
    }

    /**
     * Remove quote_detail
     *
     * @param \Core\Entity\Address quote_detail
     */
    public function removeQuoteDetail(\Core\Entity\QuoteDetail $quote_detail) {
        $this->quote_detail->removeElement($quote_detail);
    }

    /**
     * Get quote_detail
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuoteDetail() {
        return $this->quote_detail;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Quote
     */
    public function setIp($ip) {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp() {
        return $this->ip;
    }

    /**
     * Set client
     *
     * @param \Core\Entity\People $client
     * @return Quote
     */
    public function setClient(\Core\Entity\People $client = null) {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \Core\Entity\People 
     */
    public function getClient() {
        return $this->client;
    }

    /**
     * Set carrier
     *
     * @param \Core\Entity\People $carrier
     * @return Quote
     */
    public function setCarrier(\Core\Entity\People $carrier = null) {
        $this->carrier = $carrier;

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
     * Set provider
     *
     * @param \Core\Entity\People $provider
     * @return Quote
     */
    public function setProvider(\Core\Entity\People $provider = null) {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     *
     * @return \Core\Entity\People
     */
    public function getProvider() {
        return $this->provider;
    }

    /**
     * Set city_origin
     *
     * @param \Core\Entity\City $city_origin
     * @return Quote
     */
    public function setCityOrigin(\Core\Entity\City $city_origin = null) {
        $this->city_origin = $city_origin;

        return $this;
    }

    /**
     * Get city_origin
     *
     * @return \Core\Entity\City 
     */
    public function getCityOrigin() {
        return $this->city_origin;
    }

    /**
     * Set city_destination
     *
     * @param \Core\Entity\City $city_destination
     * @return Quote
     */
    public function setCityDestination(\Core\Entity\City $city_destination = null) {
        $this->city_destination = $city_destination;

        return $this;
    }

    /**
     * Get city_destination
     *
     * @return \Core\Entity\City 
     */
    public function getCityDestination() {
        return $this->city_destination;
    }

    /**
     * Set total
     *
     * @param string $total
     * @return Quote
     */
    public function setTotal($total) {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal() {
        return $this->total;
    }

    /**
     * Set order
     *
     * @param \Core\Entity\Order $order
     * @return Order
     */
    public function setOrder(\Core\Entity\Order $order = null) {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \Core\Entity\Order 
     */
    public function getOrder() {
        return $this->order;
    }

    /**
     * Set deadline
     *
     * @param integer $deadline
     * @return Quote
     */
    public function setDeadline($deadline) {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Get deadline
     *
     * @return integer
     */
    public function getDeadline() {
        return $this->deadline;
    }

    /**
     * Get denied
     *
     * @return boolean
     */
    public function getDenied() {
        return $this->denied;
    }

    /**
     * Set denied
     *
     * @param boolean $denied
     * @return Quote
     */
    public function setDenied($denied) {
        $this->denied = $denied;

        return $this;
    }

    /**
     * Set internal_ip
     *
     * @param string $internal_ip
     * @return Quote
     */
    public function setInternalIp($internal_ip) {
        $this->internal_ip = $internal_ip;

        return $this;
    }

    /**
     * Get internal_ip
     *
     * @return string
     */
    public function getInternalIp() {
        return $this->internal_ip;
    }

}
