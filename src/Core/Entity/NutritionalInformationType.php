<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NutritionalInformationType
 *
 * @ORM\Table(name="nutritional_information_type", uniqueConstraints={@ORM\UniqueConstraint(name="nutritional_information_type", columns={"nutritional_information_type"})}, indexes={@ORM\Index(name="measure_id", columns={"measure_id"})})
 * @ORM\Entity
 */
class NutritionalInformationType
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
     * @ORM\Column(name="nutritional_information_type", type="string", length=50, nullable=false)
     */
    private $nutritionalInformationType;

    /**
     * @var \Core\Entity\Measure
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Measure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="measure_id", referencedColumnName="id")
     * })
     */
    private $measure;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\NutritionalInformation", mappedBy="nutritionalInformationType")
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
     * Set nutritionalInformationType
     *
     * @param string $nutritionalInformationType
     * @return NutritionalInformationType
     */
    public function setNutritionalInformationType($nutritionalInformationType)
    {
        $this->nutritionalInformationType = $nutritionalInformationType;

        return $this;
    }

    /**
     * Get nutritionalInformationType
     *
     * @return string 
     */
    public function getNutritionalInformationType()
    {
        return $this->nutritionalInformationType;
    }

    /**
     * Set measure
     *
     * @param \Core\Entity\Measure $measure
     * @return NutritionalInformationType
     */
    public function setMeasure(\Core\Entity\Measure $measure = null)
    {
        $this->measure = $measure;

        return $this;
    }

    /**
     * Get measure
     *
     * @return \Core\Entity\Measure 
     */
    public function getMeasure()
    {
        return $this->measure;
    }

    /**
     * Add nutritionalInformation
     *
     * @param \Core\Entity\NutritionalInformation $nutritionalInformation
     * @return NutritionalInformationType
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
