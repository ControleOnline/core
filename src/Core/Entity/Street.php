<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Street
 *
 * @ORM\Table(name="street", uniqueConstraints={@ORM\UniqueConstraint(name="cep", columns={"cep_id"}), @ORM\UniqueConstraint(name="street", columns={"street", "cep_id"}), @ORM\UniqueConstraint(name="street_2", columns={"street", "neighborhood_id"})}, indexes={@ORM\Index(name="country_id", columns={"neighborhood_id"})})
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
     * @var \Core\Entity\Neighborhood
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Neighborhood")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="neighborhood_id", referencedColumnName="id")
     * })
     */
    private $neighborhood;

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
     * @ORM\OneToMany(targetEntity="Core\Entity\Adress", mappedBy="street")
     */
    private $adress;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->adress = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set neighborhood
     *
     * @param \Core\Entity\Neighborhood $neighborhood
     * @return Street
     */
    public function setNeighborhood(\Core\Entity\Neighborhood $neighborhood = null)
    {
        $this->neighborhood = $neighborhood;

        return $this;
    }

    /**
     * Get neighborhood
     *
     * @return \Core\Entity\Neighborhood 
     */
    public function getNeighborhood()
    {
        return $this->neighborhood;
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
     * Add adress
     *
     * @param \Core\Entity\Adress $adress
     * @return Street
     */
    public function addAdress(\Core\Entity\Adress $adress)
    {
        $this->adress[] = $adress;

        return $this;
    }

    /**
     * Remove adress
     *
     * @param \Core\Entity\Adress $adress
     */
    public function removeAdress(\Core\Entity\Adress $adress)
    {
        $this->adress->removeElement($adress);
    }

    /**
     * Get adress
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdress()
    {
        return $this->adress;
    }
}
