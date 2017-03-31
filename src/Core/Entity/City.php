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
     * @ORM\OneToMany(targetEntity="Core\Entity\Neighborhood", mappedBy="city")
     */
    private $neighborhood;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->neighborhood = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add neighborhood
     *
     * @param \Core\Entity\Neighborhood $neighborhood
     * @return City
     */
    public function addNeighborhood(\Core\Entity\Neighborhood $neighborhood)
    {
        $this->neighborhood[] = $neighborhood;

        return $this;
    }

    /**
     * Remove neighborhood
     *
     * @param \Core\Entity\Neighborhood $neighborhood
     */
    public function removeNeighborhood(\Core\Entity\Neighborhood $neighborhood)
    {
        $this->neighborhood->removeElement($neighborhood);
    }

    /**
     * Get neighborhood
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNeighborhood()
    {
        return $this->neighborhood;
    }
}
