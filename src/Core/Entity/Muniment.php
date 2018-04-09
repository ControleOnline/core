<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Muniment
 *
 * @ORM\Table(name="muniment", uniqueConstraints={@ORM\UniqueConstraint(name="image_id", columns={"image_id"})},indexes={@ORM\Index(name="IDX_muniment_group_id", columns={"muniment_group_id"}),@ORM\Index(name="IDX_validation_date", columns={"validation_date"}),@ORM\Index(name="IDX_alter_date", columns={"alter_date"}),@ORM\Index(name="IDX_creation_date", columns={"creation_date"})})
 * @ORM\Entity
 */
class Muniment {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Core\Entity\MunimentGroup
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Muniment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="muniment_group_id", referencedColumnName="id")
     * })
     */
    private $muniment_group;

    /**
     * @var \DateTime
     * @ORM\Column(name="creation_date", type="datetime",  nullable=false, columnDefinition="DATETIME")
     */
    private $creation_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="alter_date", type="datetime",  nullable=false, columnDefinition="DATETIME on update CURRENT_TIMESTAMP")
     */
    private $alter_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="validation_date", type="datetime",  nullable=false, columnDefinition="DATETIME")
     */
    private $validation_date;

    /**
     * @var string
     *
     * @ORM\Column(name="muniment_identifier", type="string",  nullable=false)
     */
    private $muniment_identifier = '';

    /**
     * @var string
     *
     * @ORM\Column(name="muniment", type="string",  nullable=false)
     */
    private $muniment = '';

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string",  nullable=false)
     */
    private $hash = '';

    /**
     * @var \Core\Entity\Image
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Image")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     * })
     */
    private $image;

    public function __construct() {
        $this->creation_date = new \DateTime();
        $this->alter_date = new \DateTime();
    }

    public function resetId() {
        $this->id = null;
        $this->creation_date = new \DateTime();
        $this->alter_date = new \DateTime();
        $this->validation_date = new \DateTime();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set image
     *
     * @param \Core\Entity\Image $image
     * @return People
     */
    public function setImage(\Core\Entity\Image $image = null) {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Core\Entity\Image 
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Set muniment_group
     *
     * @param \Core\Entity\MunimentGroup $muniment_group
     * @return Muniment
     */
    public function setMunimentGroup(\Core\Entity\MunimentGroup $muniment_group = null) {
        $this->muniment_group = $muniment_group;

        return $this;
    }

    /**
     * Get muniment_group
     *
     * @return \Core\Entity\MunimentGroup 
     */
    public function getMunimentGroup() {
        return $this->muniment_group;
    }

    /**
     * Get creation_date
     *
     * @return datetime
     */
    public function getCreationDate() {
        return $this->creation_date;
    }

    /**
     * Get alter_date
     *
     * @return datetime
     */
    public function getAlterDate() {
        return $this->alter_date;
    }

    /**
     * Get validation_date
     *
     * @return datetime
     */
    public function getValidationDate() {
        return $this->validation_date;
    }

    /**
     * Set validation_date
     *
     * @param datetime $validation_date
     * @return Muniment
     */
    public function setValidationDate($validation_date = null) {
        $this->validation_date = $validation_date;
        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash() {
        return $this->hash;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Muniment
     */
    public function setHash($hash = null) {
        $this->hash = $hash;
        return $this;
    }

    /**
     * Get muniment_identifier
     *
     * @return string
     */
    public function getMunimentIdentifier() {
        return $this->muniment_identifier;
    }

    /**
     * Set muniment_identifier
     *
     * @param string $muniment_identifier
     * @return Muniment
     */
    public function setMunimentIdentifier($muniment_identifier = null) {
        $this->muniment_identifier = $muniment_identifier;
        return $this;
    }

    /**
     * Get muniment
     *
     * @return string
     */
    public function getMuniment() {
        return $this->muniment;
    }

    /**
     * Set muniment
     *
     * @param string $muniment
     * @return Muniment
     */
    public function setMuniment($muniment = null) {
        $this->muniment = $muniment;
        return $this;
    }

}
