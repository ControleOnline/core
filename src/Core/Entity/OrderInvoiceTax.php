<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderInvoiceTax
 *
 * @ORM\Table(name="order_invoice_tax", uniqueConstraints={@ORM\UniqueConstraint(name="order_id", columns={"order_id", "invoice_tax_id"}),@ORM\UniqueConstraint(name="invoice_type", columns={"issuer_id", "invoice_type", "order_id"})}, indexes={@ORM\Index(name="invoice_tax_id", columns={"invoice_tax_id"})})
 * @ORM\Entity
 */
class OrderInvoiceTax {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Core\Entity\InvoiceTax
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\InvoiceTax", inversedBy="order")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="invoice_tax_id", referencedColumnName="id")
     * })
     */
    private $invoice_tax;

    /**
     * @var \Core\Entity\Order
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Order", inversedBy="invoice_tax")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     * })     
     */
    private $order;

    /**
     * @var \Core\Entity\People
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\People")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="issuer_id", referencedColumnName="id")
     * })
     */
    private $issuer;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_type", type="integer",  nullable=false)
     */
    private $invoice_type;

    public function __construct() {
        $this->order = new \Doctrine\Common\Collections\ArrayCollection();
        $this->invoice_tax = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set invoice_tax
     *
     * @param \Core\Entity\InvoiceTax $invoice_tax
     * @return OrderInvoiceTax
     */
    public function setInvoiceTax(\Core\Entity\InvoiceTax $invoice_tax = null) {
        $this->invoice_tax = $invoice_tax;

        return $this;
    }

    /**
     * Get invoice_tax
     *
     * @return \Core\Entity\InvoiceTax 
     */
    public function getInvoiceTax() {
        return $this->invoice_tax;
    }

    /**
     * Set order
     *
     * @param \Core\Entity\Order $order
     * @return OrderInvoiceTax
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
     * Set invoice_type
     *
     * @param integer $invoice_type
     * @return Order
     */
    public function setInvoiceType($invoice_type) {
        $this->invoice_type = $invoice_type;

        return $this;
    }

    /**
     * Get invoice_type
     *
     * @return integer
     */
    public function getInvoiceType() {
        return $this->invoice_type;
    }

    /**
     * Set issuer
     *
     * @param \Core\Entity\People $issuer
     * @return People
     */
    public function setIssuer(\Core\Entity\People $issuer = null) {
        $this->issuer = $issuer;

        return $this;
    }

    /**
     * Get issuer
     *
     * @return \Core\Entity\People 
     */
    public function getIssuer() {
        return $this->issuer;
    }

}
