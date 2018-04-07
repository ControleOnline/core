<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Street
 *
 * @ORM\Table(name="street", uniqueConstraints={@ORM\UniqueConstraint(name="street_2", columns={"street", "district_id"})}, indexes={@ORM\Index(name="country_id", columns={"district_id"}),@ORM\Index(name="street", columns={"street"}),@ORM\Index(name="cep", columns={"cep_id"})})
 * @ORM\Entity
 */
class Street
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
     * @ORM\Column(name="street", type="string", length=255, nullable=false)
     */
    private $street;

    /**
     * @var \Core\Entity\District
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\District")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="district_id", referencedColumnName="id")
     * })
     */
    private $district;

    /**
     * @var \Core\Entity\Cep
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Cep")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cep_id", referencedColumnName="id")
     * })
     */
    private $cep;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\Address", mappedBy="street")
     */
    private $address;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->address = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set street
     *
     * @param string $street
     * @return Street
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set district
     *
     * @param \Core\Entity\District $district
     * @return Street
     */
    public function setDistrict(\Core\Entity\District $district = null)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district
     *
     * @return \Core\Entity\District 
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Set cep
     *
     * @param \Core\Entity\Cep $cep
     * @return Street
     */
    public function setCep(\Core\Entity\Cep $cep = null)
    {
        $this->cep = $cep;

        return $this;
    }

    /**
     * Get cep
     *
     * @return \Core\Entity\Cep 
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * Add address
     *
     * @param \Core\Entity\Address $address
     * @return Street
     */
    public function addAddress(\Core\Entity\Address $address)
    {
        $this->address[] = $address;

        return $this;
    }

    /**
     * Remove address
     *
     * @param \Core\Entity\Address $address
     */
    public function removeAddress(\Core\Entity\Address $address)
    {
        $this->address->removeElement($address);
    }

    /**
     * Get address
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAddress()
    {
        return $this->address;
    }
}
