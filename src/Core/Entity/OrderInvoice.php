<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderInvoice
 *
 * @ORM\Table(name="order_invoice", uniqueConstraints={@ORM\UniqueConstraint(name="order_id", columns={"order_id", "invoice_id"})}, indexes={@ORM\Index(name="invoice_id", columns={"invoice_id"})})
 * @ORM\Entity
 */
class OrderInvoice {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @var \Core\Entity\Invoice
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Invoice", inversedBy="order")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="invoice_id", referencedColumnName="id")
     * })
     */
    private $invoice;

    /**
     * @var \Core\Entity\Order
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Order", inversedBy="invoice")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     * })     
     */
    private $order;

    public function __construct() {
        $this->order = new \Doctrine\Common\Collections\ArrayCollection();
        $this->invoice = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set invoice
     *
     * @param \Core\Entity\Invoice $invoice
     * @return OrderInvoice
     */
    public function setInvoice(\Core\Entity\Invoice $invoice = null) {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice
     *
     * @return \Core\Entity\Invoice 
     */
    public function getInvoice() {
        return $this->invoice;
    }

    /**
     * Set order
     *
     * @param \Core\Entity\Order $order
     * @return OrderInvoice
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

}
