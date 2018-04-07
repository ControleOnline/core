<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductMaterial
 *
 * @ORM\Table(name="product_material", uniqueConstraints={@ORM\UniqueConstraint(name="material", columns={"material"})})
 * @ORM\Entity
 */
class ProductMaterial {

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
     * @ORM\Column(name="material", type="string", length=50, nullable=false)
     */
    private $material;

    /**
     * @var boolean
     *
     * @ORM\Column(name="revised", type="boolean",  nullable=false)
     */
    private $revised;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set material
     *
     * @param string $material
     * @return ProductMaterial
     */
    public function setMaterial($material) {
        $this->material = $material;

        return $this;
    }

    /**
     * Get material
     *
     * @return string 
     */
    public function getMaterial() {
        return $this->material;
    }

    /**
     * Set revised
     *
     * @param string $revised
     * @return ProductMaterial
     */
    public function setRevised($revised) {
        $this->revised = $revised;

        return $this;
    }

    /**
     * Get revised
     *
     * @return boolean 
     */
    public function getRevised() {
        return $this->revised;
    }

}
