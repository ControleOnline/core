<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Measure
 *
 * @ORM\Table(name="measure", uniqueConstraints={@ORM\UniqueConstraint(name="measure", columns={"measure"})}, indexes={@ORM\Index(name="measuretype_id", columns={"measure_type_id"})})
 * @ORM\Entity
 */
class Measure
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
     * @ORM\Column(name="measure", type="string", length=50, nullable=false)
     */
    private $measure;

    /**
     * @var \Core\Entity\MeasureType
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\MeasureType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="measure_type_id", referencedColumnName="id")
     * })
     */
    private $measureType;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\NutritionalInformationType", mappedBy="measure")
     */
    private $nutritionalInformationType;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->nutritionalInformationType = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set measure
     *
     * @param string $measure
     * @return Measure
     */
    public function setMeasure($measure)
    {
        $this->measure = $measure;

        return $this;
    }

    /**
     * Get measure
     *
     * @return string 
     */
    public function getMeasure()
    {
        return $this->measure;
    }

    /**
     * Set measureType
     *
     * @param \Core\Entity\MeasureType $measureType
     * @return Measure
     */
    public function setMeasureType(\Core\Entity\MeasureType $measureType = null)
    {
        $this->measureType = $measureType;

        return $this;
    }

    /**
     * Get measureType
     *
     * @return \Core\Entity\MeasureType 
     */
    public function getMeasureType()
    {
        return $this->measureType;
    }

    /**
     * Add nutritionalInformationType
     *
     * @param \Core\Entity\NutritionalInformationType $nutritionalInformationType
     * @return Measure
     */
    public function addNutritionalInformationType(\Core\Entity\NutritionalInformationType $nutritionalInformationType)
    {
        $this->nutritionalInformationType[] = $nutritionalInformationType;

        return $this;
    }

    /**
     * Remove nutritionalInformationType
     *
     * @param \Core\Entity\NutritionalInformationType $nutritionalInformationType
     */
    public function removeNutritionalInformationType(\Core\Entity\NutritionalInformationType $nutritionalInformationType)
    {
        $this->nutritionalInformationType->removeElement($nutritionalInformationType);
    }

    /**
     * Get nutritionalInformationType
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNutritionalInformationType()
    {
        return $this->nutritionalInformationType;
    }
}
