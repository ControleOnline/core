<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Request
 *
 * @ORM\Table(name="muniment_signature", uniqueConstraints={@ORM\UniqueConstraint(name="muniment_id", columns={"muniment_id,people_id"})},indexes={@ORM\Index(name="IDX_signature_date", columns={"signature_date"}),@ORM\Index(name="people_id", columns={"people_id"})})
 * @ORM\Entity
 */
class MunimentSignature {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Core\Entity\Muniment
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Muniment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="muniment_id", referencedColumnName="id")
     * })
     */
    private $muniment;

    /**
     * @var \DateTime
     * @ORM\Column(name="creation_date", type="datetime",  nullable=false, columnDefinition="DATETIME")
     */
    private $creation_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="signature_date", type="datetime",  nullable=false, columnDefinition="DATETIME")
     */
    private $signature_date;

    /**
     * @var string
     *
     * @ORM\Column(name="details", type="string",  nullable=false)
     */
    private $details = '';

    /**
     * @var string
     *
     * @ORM\Column(name="request", type="string",  nullable=false)
     */
    private $request = '';

    /**
     * @var string
     *
     * @ORM\Column(name="confirmation", type="string",  nullable=false)
     */
    private $confirmation = '';

    /**
     * @var \Core\Entity\People
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\People")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="people_id", referencedColumnName="id")
     * })
     */
    private $people;

    public function __construct() {
        $this->creation_date = new \DateTime();
    }

    public function resetId() {
        $this->id = null;
        $this->creation_date = new \DateTime();
        $this->signature_date = new \DateTime();
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
     * Set people
     *
     * @param \Core\Entity\People $people
     * @return People
     */
    public function setPeople(\Core\Entity\People $people = null) {
        $this->people = $people;

        return $this;
    }

    /**
     * Get people
     *
     * @return \Core\Entity\People 
     */
    public function getPeople() {
        return $this->people;
    }

    /**
     * Set muniment
     *
     * @param \Core\Entity\Muniment $muniment
     * @return Request
     */
    public function setMuniment(\Core\Entity\Muniment $muniment = null) {
        $this->muniment = $muniment;

        return $this;
    }

    /**
     * Get muniment
     *
     * @return \Core\Entity\Muniment 
     */
    public function getMuniment() {
        return $this->muniment;
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
     * Get signature_date
     *
     * @return datetime
     */
    public function getSignatureDate() {
        return $this->signature_date;
    }

    /**
     * Set signature_date
     *
     * @param datetime $signature_date
     * @return Request
     */
    public function setSignatureDate($signature_date = null) {
        $this->signature_date = $signature_date;
        return $this;
    }

    /**
     * Get confirmation
     *
     * @return string
     */
    public function getConfirmation() {
        return $this->confirmation;
    }

    /**
     * Set confirmation
     *
     * @param string $confirmation
     * @return Request
     */
    public function setConfirmation($confirmation = null) {
        $this->confirmation = $confirmation;
        return $this;
    }

    /**
     * Get details
     *
     * @return string
     */
    public function getDetails() {
        return $this->details;
    }

    /**
     * Set details
     *
     * @param string $details
     * @return Request
     */
    public function setDetails($details = null) {
        $this->details = $details;
        return $this;
    }

    /**
     * Get request
     *
     * @return string
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * Set request
     *
     * @param string $request
     * @return Request
     */
    public function setRequest($request = null) {
        $this->request = $request;
        return $this;
    }

}
