<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvoiceStatus
 *
 * @ORM\Table(name="invoice_status", uniqueConstraints={@ORM\UniqueConstraint(name="status", columns={"status"})}, indexes={@ORM\Index(name="IDX_real_status", columns={"real_status"})})
 * @ORM\Entity
 */
class InvoiceStatus {

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
     * @ORM\Column(name="status", type="string",  nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="real_status", type="string",  nullable=false)
     */
    private $real_status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="notify", type="boolean",  nullable=false)
     */
    private $notify;

    /**
     * @var boolean
     *
     * @ORM\Column(name="system", type="boolean",  nullable=false)
     */
    private $system;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return InvoiceStatus
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set real_status
     *
     * @param string $real_status
     * @return InvoiceStatus
     */
    public function setRealStatus($real_status) {
        $this->real_status = $real_status;

        return $this;
    }

    /**
     * Get real_status
     *
     * @return string
     */
    public function getRealStatus() {
        return $this->real_status;
    }

    /**
     * Set notify
     *
     * @param boolean $notify
     * @return InvoiceStatus
     */
    public function setNotify($notify) {
        $this->notify = $notify;

        return $this;
    }

    /**
     * Get notify
     *
     * @return boolean
     */
    public function getNotify() {
        return $this->notify;
    }

    /**
     * Set system
     *
     * @param boolean $system
     * @return InvoiceStatus
     */
    public function setSystem($system) {
        $this->system = $system;

        return $this;
    }

    /**
     * Get system
     *
     * @return boolean
     */
    public function getSystem() {
        return $this->system;
    }

}
