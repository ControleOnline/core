<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * District
 *
 * @ORM\Table(name="district", indexes={@ORM\Index(name="city_id", columns={"city_id"})})
 * @ORM\Entity
 */
class District
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
     * @ORM\Column(name="district", type="string", length=255, nullable=false)
     */
    private $district;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\Street", mappedBy="district")
     */
    private $street;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->street = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set district
     *
     * @param string $district
     * @return District
     */
    public function setDistrict($district)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district
     *
     * @return string 
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Set city
     *
     * @param \Core\Entity\City $city
     * @return District
     */
    public function setCity(\Core\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \Core\Entity\City 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Add street
     *
     * @param \Core\Entity\Street $street
     * @return District
     */
    public function addStreet(\Core\Entity\Street $street)
    {
        $this->street[] = $street;

        return $this;
    }

    /**
     * Remove street
     *
     * @param \Core\Entity\Street $street
     */
    public function removeStreet(\Core\Entity\Street $street)
    {
        $this->street->removeElement($street);
    }

    /**
     * Get street
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStreet()
    {
        return $this->street;
    }
}
