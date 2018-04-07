<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Order
 *
 * @ORM\Table(name="orders", uniqueConstraints={@ORM\UniqueConstraint(name="quote_id", columns={"quote_id"})}, indexes={@ORM\Index(name="IDX_client_id", columns={"client_id"}),@ORM\Index(name="IDX_order_status_id", columns={"order_status_id"}),@ORM\Index(name="IDX_order_date", columns={"order_date"}),@ORM\Index(name="IDX_alter_date", columns={"alter_date"}),@ORM\Index(name="IDX_payer_people_id", columns={"payer_people_id"}),@ORM\Index(name="IDX_delivery_people_id", columns={"delivery_people_id"}),@ORM\Index(name="IDX_retrieve_people_id", columns={"retrieve_people_id"}),@ORM\Index(name="IDX_provider_id", columns={"provider_id"}),@ORM\Index(name="IDX_client_id", columns={"client_id"}),@ORM\Index(name="IDX_quote_id", columns={"quote_id"})})
 * @ORM\Entity
 */
class Order {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

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
     * @var \DateTime
     * @ORM\Column(name="order_date", type="datetime",  nullable=false, columnDefinition="DATETIME")
     */
    private $order_date;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\OrderInvoice", mappedBy="order")     
     */
    private $invoice;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\OrderInvoiceTax", mappedBy="order")
     */
    private $invoice_tax;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="alter_date", type="datetime",  nullable=false, columnDefinition="DATETIME on update CURRENT_TIMESTAMP")
     */
    private $alter_date;

    /**
     * @var \Core\Entity\OrderStatus
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\OrderStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_status_id", referencedColumnName="id")
     * })
     */
    private $order_status;

    /**
     * @var \Core\Entity\People
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\People")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="delivery_people_id", referencedColumnName="id")
     * })
     */
    private $delivery_people;

    /**
     * @var \Core\Entity\People
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\People")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="retrieve_people_id", referencedColumnName="id")
     * })
     */
    private $retrieve_people;

    /**
     * @var \Core\Entity\People
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\People")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="payer_people_id", referencedColumnName="id")
     * })
     */
    private $payer;

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
     * @var \Core\Entity\Quote
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Quote")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="quote_id", referencedColumnName="id")
     * })
     */
    private $quote;

    /**
     * @var \Core\Entity\Address
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Address")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="address_origin_id", referencedColumnName="id")
     * })
     */
    private $address_origin;

    /**
     * @var \Core\Entity\Address
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Address")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="address_destination_id", referencedColumnName="id")
     * })
     */
    private $address_destination;

    /**
     * @var \Core\Entity\People
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\People")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="retrieve_contact_id", referencedColumnName="id")
     * })
     */
    private $retrieve_contact;

    /**
     * @var \Core\Entity\People
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\People")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="delivery_contact_id", referencedColumnName="id")
     * })
     */
    private $delivery_contact;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\OrderPackage", mappedBy="order")
     */
    private $order_package;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float",  nullable=true)
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(name="invoice_total", type="float",  nullable=false)
     */
    private $invoice_total = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="cubage", type="float",  nullable=false)
     */
    private $cubage = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="product_type", type="string",  nullable=false)
     */
    private $product_type = '';

    /**
     * @var string
     *
     * @ORM\Column(name="comments", type="string",  nullable=true)
     */
    private $comments;

    public function __construct() {
        $this->order_date = new \DateTime();
        $this->alter_date = new \DateTime();
        $this->order_package = new \Doctrine\Common\Collections\ArrayCollection();
        $this->invoice_tax = new \Doctrine\Common\Collections\ArrayCollection();
        $this->invoice = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function resetId() {
        $this->id = null;
        $this->order_date = new \DateTime();
        $this->alter_date = new \DateTime();
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
     * Set order_status
     *
     * @param \Core\Entity\OrderStatus $order_status
     * @return Order
     */
    public function setStatus(\Core\Entity\OrderStatus $order_status = null) {
        $this->order_status = $order_status;

        return $this;
    }

    /**
     * Get order_status
     *
     * @return \Core\Entity\OrderStatus
     */
    public function getStatus() {
        return $this->order_status;
    }

    /**
     * Set client
     *
     * @param \Core\Entity\People $client
     * @return Order
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
     * Set provider
     *
     * @param \Core\Entity\People $provider
     * @return Order
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
     * Set price
     *
     * @param float $price
     * @return Order
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
     * Set address_origin
     *
     * @param \Core\Entity\Address $address_origin
     * @return Order
     */
    public function setAddressOrigin(\Core\Entity\Address $address_origin = null) {
        $this->address_origin = $address_origin;

        return $this;
    }

    /**
     * Get address_origin
     *
     * @return \Core\Entity\Address 
     */
    public function getAddressOrigin() {
        return $this->address_origin;
    }

    /**
     * Set address_destination
     *
     * @param \Core\Entity\Address $address_destination
     * @return Order
     */
    public function setAddressDestination(\Core\Entity\Address $address_destination = null) {
        $this->address_destination = $address_destination;

        return $this;
    }

    /**
     * Get quote
     *
     * @return \Core\Entity\Address 
     */
    public function getAddressDestination() {
        return $this->address_destination;
    }

    /**
     * Get retrieve_contact
     *
     * @return \Core\Entity\People 
     */
    public function getRetrieveContact() {
        return $this->retrieve_contact;
    }

    /**
     * Set retrieve_contact
     *
     * @param \Core\Entity\People $retrieve_contact
     * @return Order
     */
    public function setRetrieveContact(\Core\Entity\People $retrieve_contact = null) {
        $this->retrieve_contact = $retrieve_contact;

        return $this;
    }

    /**
     * Get delivery_contact
     *
     * @return \Core\Entity\People 
     */
    public function getDeliveryContact() {
        return $this->delivery_contact;
    }

    /**
     * Set delivery_contact
     *
     * @param \Core\Entity\People $delivery_contact
     * @return Order
     */
    public function setDeliveryContact(\Core\Entity\People $delivery_contact = null) {
        $this->delivery_contact = $delivery_contact;

        return $this;
    }

    /**
     * Set payer
     *
     * @param \Core\Entity\People $payer
     * @return Order
     */
    public function setPayer(\Core\Entity\People $payer = null) {
        $this->payer = $payer;

        return $this;
    }

    /**
     * Get payer
     *
     * @return \Core\Entity\People 
     */
    public function getPayer() {
        return $this->payer;
    }

    /**
     * Set delivery_people
     *
     * @param \Core\Entity\People $delivery_people
     * @return Order
     */
    public function setDeliveryPeople(\Core\Entity\People $delivery_people = null) {
        $this->delivery_people = $delivery_people;

        return $this;
    }

    /**
     * Get delivery_people
     *
     * @return \Core\Entity\People 
     */
    public function getDeliveryPeople() {
        return $this->delivery_people;
    }

    /**
     * Set retrieve_people
     *
     * @param \Core\Entity\People $retrieve_people
     * @return Order
     */
    public function setRetrievePeople(\Core\Entity\People $retrieve_people = null) {
        $this->retrieve_people = $retrieve_people;

        return $this;
    }

    /**
     * Get retrieve_people
     *
     * @return \Core\Entity\People 
     */
    public function getRetrievePeople() {
        return $this->retrieve_people;
    }

    /**
     * Set comments
     *
     * @param string $comments
     * @return Order
     */
    public function setComments($comments) {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string
     */
    public function getComments() {
        return $this->comments;
    }

    /**
     * Get order_date
     *
     * @return datetime
     */
    public function getOrderDate() {
        return $this->order_date;
    }

    /**
     * Get alter_date
     *
     * @return datetime
     */
    public function getAlterDate() {
        return $this->alter_date;
    }

    /**
     * Add order_package
     *
     * @param \Core\Entity\OrderPackage $order_package
     * @return Order
     */
    public function addOrderPackage(\Core\Entity\OrderPackage $order_package) {
        $this->order_package[] = $order_package;

        return $this;
    }

    /**
     * Remove order_package
     *
     * @param \Core\Entity\OrderPackage $order_package
     */
    public function removeOrderPackage(\Core\Entity\OrderPackage $order_package) {
        $this->address->removeElement($order_package);
    }

    /**
     * Get order_package
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrderPackage() {
        return $this->order_package;
    }

    /**
     * Add invoice_tax
     *
     * @param \Core\Entity\OrderInvoiceTax $invoice_tax
     * @return Order
     */
    public function addAInvoiceTax(\Core\Entity\OrderInvoiceTax $invoice_tax) {
        $this->invoice_tax[] = $invoice_tax;

        return $this;
    }

    /**
     * Remove invoice_tax
     *
     * @param \Core\Entity\OrderInvoiceTax $invoice_tax
     */
    public function removeInvoiceTax(\Core\Entity\OrderInvoiceTax $invoice_tax) {
        $this->address->removeElement($invoice_tax);
    }

    /**
     * Get invoice_tax
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInvoiceTax() {
        return $this->invoice_tax;
    }

    /**
     * Add OrderInvoice
     *
     * @param \Core\Entity\OrderInvoice $invoice
     * @return People
     */
    public function addInvoice(\Core\Entity\OrderInvoice $invoice) {
        $this->invoice[] = $invoice;

        return $this;
    }

    /**
     * Remove OrderInvoice
     *
     * @param \Core\Entity\OrderInvoice $invoice
     */
    public function removeInvoice(\Core\Entity\OrderInvoice $invoice) {
        $this->invoice->removeElement($invoice);
    }

    /**
     * Get OrderInvoice
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInvoice() {
        return $this->invoice;
    }

    /**
     * Set invoice_total
     *
     * @param float $invoice_total
     * @return Order
     */
    public function setInvoiceTotal($invoice_total) {
        $this->invoice_total = $invoice_total;

        return $this;
    }

    /**
     * Get invoice_total
     *
     * @return float
     */
    public function getInvoiceTotal() {
        return $this->invoice_total;
    }

    /**
     * Set cubage
     *
     * @param float $cubage
     * @return Order
     */
    public function setCubage($cubage) {
        $this->cubage = $cubage;

        return $this;
    }

    /**
     * Get cubage
     *
     * @return float
     */
    public function getCubage() {
        return $this->cubage;
    }

    /**
     * Set product_type
     *
     * @param string $product_type
     * @return Order
     */
    public function setProductType($product_type) {
        $this->product_type = $product_type;

        return $this;
    }

    /**
     * Get product_type
     *
     * @return string
     */
    public function getProductType() {
        return $this->product_type;
    }

}
