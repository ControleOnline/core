<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Order
 *
 * @ORM\Table(name="invoice_tax", indexes={@ORM\Index(name="IDX_issuer_id", columns={"issuer_id"})})
 * @ORM\Entity
 */
class InvoiceTax {

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
     * @ORM\OneToMany(targetEntity="Core\Entity\OrderInvoiceTax", mappedBy="invoice_tax")     
     */
    private $order;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice", type="string",  nullable=false)
     */
    private $invoice;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\ServiceInvoiceTax", mappedBy="service_invoice_tax")     
     */
    private $service_invoice_tax;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_number", type="integer",  nullable=false)
     */
    private $invoice_number;

    public function __construct() {
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
     * Set invoice
     *
     * @param string $invoice
     * @return Order
     */
    public function setInvoice($invoice) {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice
     *
     * @return string
     */
    public function getInvoice() {
        return $this->invoice;
    }

    /**
     * Set invoice_number
     *
     * @param integer $invoice_number
     * @return Order
     */
    public function setInvoiceNumber($invoice_number) {
        $this->invoice_number = $invoice_number;

        return $this;
    }

    /**
     * Get invoice_number
     *
     * @return integer
     */
    public function getInvoiceNumber() {
        return $this->invoice_number;
    }

    /**
     * Add ServiceInvoiceTax
     *
     * @param \Core\Entity\ServiceInvoiceTax $service_invoice_tax
     * @return InvoiceTax
     */
    public function addServiceInvoiceTax(\Core\Entity\ServiceInvoiceTax $service_invoice_tax) {
        $this->service_invoice_tax[] = $service_invoice_tax;
        return $this;
    }

    /**
     * Remove ServiceInvoiceTax
     *
     * @param \Core\Entity\ServiceInvoiceTax $service_invoice_tax
     */
    public function removeServiceInvoiceTax(\Core\Entity\ServiceInvoiceTax $service_invoice_tax) {
        $this->service_invoice_tax->removeElement($service_invoice_tax);
    }

    /**
     * Get ServiceInvoiceTax
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServiceInvoiceTax() {
        return $this->service_invoice_tax;
    }

}
