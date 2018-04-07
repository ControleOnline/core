<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeliveryRestrictionMaterial
 *
 * @ORM\Table(name="delivery_restriction_material", uniqueConstraints={@ORM\UniqueConstraint(name="people_id", columns={"people_id","product_material_id"})}, indexes={@ORM\Index(name="product_material_id", columns={"product_material_id"})})
 * @ORM\Entity
 */
class DeliveryRestrictionMaterial {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Core\Entity\PeopleCarrier
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\People")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="people_id", referencedColumnName="id")
     * })
     */
    private $carrier;

    /**
     * @var \Core\Entity\ProductMaterial
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\ProductMaterial")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_material_id", referencedColumnName="id")
     * })
     */
    private $product_material;

    /**
     * @var string
     *
     * @ORM\Column(name="restriction_type", type="string", length=50, nullable=false)
     */
    private $restriction_type;

    public function __construct() {
        $this->region_city = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set carrier
     *
     * @param \Core\Entity\People $carrier
     * @return DeliveryRestrictionMaterial
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
     * Set material
     *
     * @param \Core\Entity\ProductMaterial $product_material
     * @return DeliveryRestrictionMaterial
     */
    public function setProductMaterial(\Core\Entity\ProductMaterial $product_material = null) {
        $this->product_material = $product_material;

        return $this;
    }

    /**
     * Get material
     *
     * @return \Core\Entity\ProductMaterial 
     */
    public function getProductMaterial() {
        return $this->product_material;
    }

    /**
     * Set restriction_type
     *
     * @param string $restriction_type
     * @return RestrictionType
     */
    public function setRestrictionType($restriction_type) {
        $this->restriction_type = $restriction_type;

        return $this;
    }

    /**
     * Get restriction_type
     *
     * @return string 
     */
    public function getRestrictionType() {
        return $this->restriction_type;
    }

}
