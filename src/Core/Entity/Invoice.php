<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Invoice
 *
 * @ORM\Table(name="invoice", indexes={@ORM\Index(name="invoice_subtype", columns={"invoice_subtype"}),@ORM\Index(name="invoice", columns={"invoice"})})
 * @ORM\Entity
 */
class Invoice {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\OrderInvoice", mappedBy="invoice")     
     */
    private $order;

    /**
     * @var \Core\Entity\OrderStatus
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\InvoiceStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="invoice_status_id", referencedColumnName="id")
     * })
     */
    private $invoice_status;

    /**
     * @var \DateTime
     * @ORM\Column(name="invoice_date", type="datetime",  nullable=false, columnDefinition="DATETIME")
     */
    private $invoice_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="alter_date", type="datetime",  nullable=false, columnDefinition="DATETIME on update CURRENT_TIMESTAMP")
     */
    private $alter_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="due_date", type="datetime",  nullable=false, columnDefinition="DATETIME")
     */
    private $due_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="payment_date", type="datetime",  nullable=true, columnDefinition="DATETIME")
     */
    private $payment_date;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float",  nullable=true)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_type", type="string",  nullable=false)
     */
    private $invoice_type;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_subtype", type="string",  nullable=false)
     */
    private $invoice_subtype;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_response", type="string",  nullable=false)
     */
    private $payment_response;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\ServiceInvoiceTax", mappedBy="invoice")
     */
    private $service_invoice_tax;

    public function __construct() {
        $this->invoice_date = new \DateTime();
        $this->alter_date = new \DateTime();
        $this->due_date = new \DateTime();
        $this->payment_date = new \DateTime();
        $this->order = new \Doctrine\Common\Collections\ArrayCollection();
        $this->service_invoice_tax = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set invoice_type
     *
     * @param string $invoice_type
     * @return Order
     */
    public function setInvoiceType($invoice_type) {
        $this->invoice_type = $invoice_type;

        return $this;
    }

    /**
     * Get invoice_type
     *
     * @return string
     */
    public function getInvoiceType() {
        return $this->invoice_type;
    }

    /**
     * Set payment_response
     *
     * @param string $payment_response
     * @return Invoice
     */
    public function setPaymentResponse($payment_response) {
        $this->payment_response = $payment_response;

        return $this;
    }

    /**
     * Get payment_response
     *
     * @return string
     */
    public function getPaymentResponse() {
        return $this->payment_response;
    }

    /**
     * Add OrderInvoice
     *
     * @param \Core\Entity\OrderInvoice $order
     * @return People
     */
    public function addOrder(\Core\Entity\OrderInvoice $order) {
        $this->order[] = $order;

        return $this;
    }

    /**
     * Remove OrderInvoice
     *
     * @param \Core\Entity\OrderInvoice $order
     */
    public function removeOrder(\Core\Entity\OrderInvoice $order) {
        $this->order->removeElement($order);
    }

    /**
     * Get OrderInvoice
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrder() {
        return $this->order;
    }

    /**
     * Set invoice_subtype
     *
     * @param string $invoice_subtype
     * @return Invoice
     */
    public function setInvoiceSubtype($invoice_subtype) {
        $this->invoice_subtype = $invoice_subtype;

        return $this;
    }

    /**
     * Get invoice_subtype
     *
     * @return string
     */
    public function getInvoiceSubtype() {
        return $this->invoice_subtype;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Invoice
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
     * Get invoice_date
     *
     * @return datetime
     */
    public function getInvoiceDate() {
        return $this->invoice_date;
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
     * Get due_date
     *
     * @return datetime
     */
    public function getDueDate() {
        return $this->due_date;
    }

    /**
     * Get payment_date
     *
     * @return datetime
     */
    public function getPaymentDate() {
        return $this->payment_date;
    }

    /**
     * Set due_date
     *
     * @param \DateTime $due_date
     * @return Invoice
     */
    public function setDueDate(\DateTime $due_date) {
        $this->due_date = $due_date;

        return $this;
    }

    /**
     * Set price
     *
     * @param \DateTime $payment_date
     * @return Invoice
     */
    public function setPaymentDate($payment_date) {
        $this->payment_date = $payment_date;

        return $this;
    }

    /**
     * Set invoice_status
     *
     * @param \Core\Entity\InvoiceStatus $invoice_status
     * @return Order
     */
    public function setStatus(\Core\Entity\InvoiceStatus $invoice_status = null) {
        $this->invoice_status = $invoice_status;

        return $this;
    }

    /**
     * Get invoice_status
     *
     * @return \Core\Entity\InvoiceStatus
     */
    public function getStatus() {
        return $this->invoice_status;
    }

    /**
     * Add service_invoice_tax
     *
     * @param \Core\Entity\ServiceInvoiceTax $service_invoice_tax
     * @return Order
     */
    public function addAServiceInvoiceTax(\Core\Entity\ServiceInvoiceTax $service_invoice_tax) {
        $this->service_invoice_tax[] = $service_invoice_tax;

        return $this;
    }

    /**
     * Remove service_invoice_tax
     *
     * @param \Core\Entity\ServiceInvoiceTax $service_invoice_tax
     */
    public function removeServiceInvoiceTax(\Core\Entity\ServiceInvoiceTax $service_invoice_tax) {
        $this->address->removeElement($service_invoice_tax);
    }

    /**
     * Get service_invoice_tax
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServiceInvoiceTax() {
        return $this->service_invoice_tax;
    }

}
