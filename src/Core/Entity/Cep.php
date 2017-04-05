<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cep
 *
 * @ORM\Table(name="cep", uniqueConstraints={@ORM\UniqueConstraint(name="CEP", columns={"cep"})})
 * @ORM\Entity
 */
class Cep
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
     * @var integer
     *
     * @ORM\Column(name="cep", type="integer", nullable=false)
     */
    private $cep;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\Street", mappedBy="street")
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
     * Set cep
     *
     * @param integer $cep
     * @return Cep
     */
    public function setCep($cep)
    {
        $this->cep = $cep;

        return $this;
    }

    /**
     * Get cep
     *
     * @return integer 
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * Add street
     *
     * @param \Core\Entity\Street $street
     * @return Cep
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
