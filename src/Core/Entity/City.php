<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * City
 *
 * @ORM\Table(name="city", indexes={@ORM\Index(name="state_id", columns={"state_id"})})
 * @ORM\Entity
 */
class City
{
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
     * @ORM\Column(name="city", type="string", length=80, nullable=false)
     */
    private $city;

    /**
     * @var \Core\Entity\State
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\State")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="state_id", referencedColumnName="id")
     * })
     */
    private $state;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\District", mappedBy="city")
     */
    private $district;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->district = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return City
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param \Core\Entity\State $state
     * @return City
     */
    public function setState(\Core\Entity\State $state = null)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return \Core\Entity\State 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Add district
     *
     * @param \Core\Entity\District $district
     * @return City
     */
    public function addDistrict(\Core\Entity\District $district)
    {
        $this->district[] = $district;

        return $this;
    }

    /**
     * Remove district
     *
     * @param \Core\Entity\District $district
     */
    public function removeDistrict(\Core\Entity\District $district)
    {
        $this->district->removeElement($district);
    }

    /**
     * Get district
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDistrict()
    {
        return $this->district;
    }
}
