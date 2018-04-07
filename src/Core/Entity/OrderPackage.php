<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderPackage
 *
 * @ORM\Table(name="order_package", indexes={@ORM\Index(name="IDX_order_id", columns={"order_id"})})
 * @ORM\Entity
 */
class OrderPackage {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

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
     * @ORM\Column(name="qtd", type="float",  nullable=true)
     */
    private $qtd;

    /**
     * @var float
     *
     * @ORM\Column(name="height", type="float",  nullable=true)
     */
    private $height;

    /**
     * @var float
     *
     * @ORM\Column(name="width", type="float",  nullable=true)
     */
    private $width;

    /**
     * @var float
     *
     * @ORM\Column(name="depth", type="float",  nullable=true)
     */
    private $depth;

    /**
     * @var float
     *
     * @ORM\Column(name="weight", type="float",  nullable=true)
     */
    private $weight;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set order
     *
     * @param \Core\Entity\Order $order
     * @return OrderPackage
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
     * Set qtd
     *
     * @param string $qtd
     * @return OrderPackage
     */
    public function setQtd($qtd) {
        $this->qtd = $qtd;

        return $this;
    }

    /**
     * Get qtd
     *
     * @return float
     */
    public function getQtd() {
        return $this->qtd;
    }

    /**
     * Set height
     *
     * @param string $height
     * @return OrderPackage
     */
    public function setHeight($height) {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return float
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * Set width
     *
     * @param string $width
     * @return OrderPackage
     */
    public function setWidth($width) {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return float
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * Set depth
     *
     * @param string $depth
     * @return OrderPackage
     */
    public function setDepth($depth) {
        $this->depth = $depth;

        return $this;
    }

    /**
     * Get depth
     *
     * @return float
     */
    public function getDepth() {
        return $this->depth;
    }

    /**
     * Set weight
     *
     * @param string $weight
     * @return OrderPackage
     */
    public function setWeight($weight) {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return float
     */
    public function getWeight() {
        return $this->weight;
    }

}
