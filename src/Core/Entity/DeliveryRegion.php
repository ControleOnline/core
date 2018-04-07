<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeliveryRegion
 *
 * @ORM\Table(name="delivery_region", uniqueConstraints={@ORM\UniqueConstraint(name="region_id", columns={"region_id","people_id"})})
 * @ORM\Entity
 */
class DeliveryRegion {

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
     * @ORM\Column(name="region", type="string", length=255, nullable=false)
     */
    private $region;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\DeliveryRegionCity", mappedBy="region")
     */
    private $region_city;

    /**
     * @var \Core\Entity\People
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\People")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="people_id", referencedColumnName="id")
     * })
     */
    private $people;

    /**
     * @var integer
     *
     * @ORM\Column(name="deadline", type="integer", length=3, nullable=false)
     */
    private $deadline;

    /**
     * @var float
     *
     * @ORM\Column(name="retrieve_tax", type="float",  nullable=true)
     */
    private $retrieve_tax;

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
     * Set region
     *
     * @param string $region
     * @return DeliveryRegion
     */
    public function setRegion($region) {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string 
     */
    public function getRegion() {
        return $this->region;
    }

    /**
     * Set people
     *
     * @param \Core\Entity\People $people
     * @return Document
     */
    public function setPeople(\Core\Entity\People $people = null) {
        $this->people = $people;

        return $this;
    }

    /**
     * Get people
     *
     * @return \Core\Entity\People 
     */
    public function getPeople() {
        return $this->people;
    }

    /**
     * Add region_city
     *
     * @param \Core\Entity\DeliveryRegionCity $region_city
     * @return DeliveryRegion
     */
    public function addRegionCity(\Core\Entity\DeliveryRegionCity $region_city) {
        $this->region_city[] = $region_city;

        return $this;
    }

    /**
     * Remove region_city
     *
     * @param \Core\Entity\DeliveryRegionCity $region_city
     */
    public function removeRegionCity(\Core\Entity\DeliveryRegionCity $region_city) {
        $this->region_city->removeElement($region_city);
    }

    /**
     * Get region_city
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRegionCity() {
        return $this->region_city;
    }

    /**
     * Set deadline
     *
     * @param string $deadline
     * @return DeliveryRegion
     */
    public function setDeadline($deadline) {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Get deadline
     *
     * @return string 
     */
    public function getDeadline() {
        return $this->deadline;
    }

    /**
     * Set retrieve_tax
     *
     * @param string $retrieve_tax
     * @return DeliveryRegion
     */
    public function setRetrieveTax($retrieve_tax) {
        $this->retrieve_tax = $retrieve_tax;

        return $this;
    }

    /**
     * Get retrieve_tax
     *
     * @return string 
     */
    public function getRetrieveTax() {
        return $this->retrieve_tax;
    }

}
