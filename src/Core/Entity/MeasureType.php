<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MeasureType
 *
 * @ORM\Table(name="measure_type", uniqueConstraints={@ORM\UniqueConstraint(name="measure_type", columns={"measure_type"})})
 * @ORM\Entity
 */
class MeasureType
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
     * @ORM\Column(name="measure_type", type="string", length=50, nullable=false)
     */
    private $measureType;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\Measure", mappedBy="measureType")
     */
    private $measure;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->measure = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set measureType
     *
     * @param string $measureType
     * @return MeasureType
     */
    public function setMeasureType($measureType)
    {
        $this->measureType = $measureType;

        return $this;
    }

    /**
     * Get measureType
     *
     * @return string 
     */
    public function getMeasureType()
    {
        return $this->measureType;
    }

    /**
     * Add measure
     *
     * @param \Core\Entity\Measure $measure
     * @return MeasureType
     */
    public function addMeasure(\Core\Entity\Measure $measure)
    {
        $this->measure[] = $measure;

        return $this;
    }

    /**
     * Remove measure
     *
     * @param \Core\Entity\Measure $measure
     */
    public function removeMeasure(\Core\Entity\Measure $measure)
    {
        $this->measure->removeElement($measure);
    }

    /**
     * Get measure
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMeasure()
    {
        return $this->measure;
    }
}
