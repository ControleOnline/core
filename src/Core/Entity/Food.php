<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Food
 *
 * @ORM\Table(name="food", uniqueConstraints={@ORM\UniqueConstraint(name="food", columns={"food"})})
 * @ORM\Entity
 */
class Food
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
     * @ORM\Column(name="food", type="string", length=50, nullable=false)
     */
    private $food;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\NutritionalInformation", mappedBy="food")
     */
    private $nutritionalInformation;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->nutritionalInformation = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set food
     *
     * @param string $food
     * @return Food
     */
    public function setFood($food)
    {
        $this->food = $food;

        return $this;
    }

    /**
     * Get food
     *
     * @return string 
     */
    public function getFood()
    {
        return $this->food;
    }

    /**
     * Add nutritionalInformation
     *
     * @param \Core\Entity\NutritionalInformation $nutritionalInformation
     * @return Food
     */
    public function addNutritionalInformation(\Core\Entity\NutritionalInformation $nutritionalInformation)
    {
        $this->nutritionalInformation[] = $nutritionalInformation;

        return $this;
    }

    /**
     * Remove nutritionalInformation
     *
     * @param \Core\Entity\NutritionalInformation $nutritionalInformation
     */
    public function removeNutritionalInformation(\Core\Entity\NutritionalInformation $nutritionalInformation)
    {
        $this->nutritionalInformation->removeElement($nutritionalInformation);
    }

    /**
     * Get nutritionalInformation
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNutritionalInformation()
    {
        return $this->nutritionalInformation;
    }
}
