<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeliveryRegionCity
 *
 * @ORM\Table(name="delivery_region_city", uniqueConstraints={@ORM\UniqueConstraint(name="delivery_region_city", columns={"delivery_region_city","city_id"})}, indexes={@ORM\Index(name="city_id", columns={"city_id"})})
 * @ORM\Entity
 */
class DeliveryRegionCity {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Core\Entity\City
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\DeliveryRegion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="delivery_region_id", referencedColumnName="id")
     * })
     */
    private $region;

    /**
     * @var \Core\Entity\City
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\City")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     * })
     */
    private $city;

    /**
     * Constructor
     */
    public function __construct() {
        //$this->city = new \Doctrine\Common\Collections\ArrayCollection();
        //$this->region = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add city
     *
     * @param \Core\Entity\City $city
     * @return DeliveryRegionCity
     */
    public function setCity(\Core\Entity\City $city) {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \Core\Entity\DeliveryRegion 
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Add region
     *
     * @param \Core\Entity\DeliveryRegion $region
     * @return DeliveryRegionCity
     */
    public function setRegion(\Core\Entity\DeliveryRegion $region) {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return \Core\Entity\DeliveryRegion 
     */
    public function getRegion() {
        return $this->region;
    }

}
