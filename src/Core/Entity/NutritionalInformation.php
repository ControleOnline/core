<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NutritionalInformation
 *
 * @ORM\Table(name="nutritional_information", uniqueConstraints={@ORM\UniqueConstraint(name="food_id", columns={"food_id", "nutritional_information_type_id"})}, indexes={@ORM\Index(name="nutritional_information_type_id", columns={"nutritional_information_type_id"}), @ORM\Index(name="IDX_F35B1E40BA8E87C4", columns={"food_id"})})
 * @ORM\Entity
 */
class NutritionalInformation
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
     * @var float
     *
     * @ORM\Column(name="amount", type="float", precision=10, scale=0, nullable=false)
     */
    private $amount;

    /**
     * @var \Core\Entity\NutritionalInformationType
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\NutritionalInformationType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="nutritional_information_type_id", referencedColumnName="id")
     * })
     */
    private $nutritionalInformationType;

    /**
     * @var \Core\Entity\Food
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Food")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="food_id", referencedColumnName="id")
     * })
     */
    private $food;


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
     * Set amount
     *
     * @param float $amount
     * @return NutritionalInformation
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set nutritionalInformationType
     *
     * @param \Core\Entity\NutritionalInformationType $nutritionalInformationType
     * @return NutritionalInformation
     */
    public function setNutritionalInformationType(\Core\Entity\NutritionalInformationType $nutritionalInformationType = null)
    {
        $this->nutritionalInformationType = $nutritionalInformationType;

        return $this;
    }

    /**
     * Get nutritionalInformationType
     *
     * @return \Core\Entity\NutritionalInformationType 
     */
    public function getNutritionalInformationType()
    {
        return $this->nutritionalInformationType;
    }

    /**
     * Set food
     *
     * @param \Core\Entity\Food $food
     * @return NutritionalInformation
     */
    public function setFood(\Core\Entity\Food $food = null)
    {
        $this->food = $food;

        return $this;
    }

    /**
     * Get food
     *
     * @return \Core\Entity\Food 
     */
    public function getFood()
    {
        return $this->food;
    }
}
