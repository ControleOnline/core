<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeopleProcurator
 *
 * @ORM\Table(name="people_procurator", uniqueConstraints={@ORM\UniqueConstraint(name="procurator_id", columns={"procurator_id", "grantor_id"})}, indexes={@ORM\Index(name="grantor_id", columns={"grantor_id"}), @ORM\Index(name="IDX_2C6E59348C03F15C", columns={"procurator_id"})})
 * @ORM\Entity
 */
class PeopleProcurator {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Core\Entity\People
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\People", inversedBy="peopleGrantor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="grantor_id", referencedColumnName="id")
     * })
     */
    private $grantor;

    /**
     * @var \Core\Entity\People
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\People", inversedBy="peopleProcurator")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="procurator_id", referencedColumnName="id")
     * })
     * @ORM\OrderBy({"alias" = "ASC"})
     */
    private $procurator;

    /**
     * @var \Core\Entity\MunimentSignature
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\MunimentSignature")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="muniment_signature_id", referencedColumnName="id")
     * })
     */
    private $muniment_signature;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set grantor
     *
     * @param \Core\Entity\People $grantor
     * @return PeopleProcurator
     */
    public function setGrantor(\Core\Entity\People $grantor = null) {
        $this->grantor = $grantor;

        return $this;
    }

    /**
     * Get grantor
     *
     * @return \Core\Entity\People 
     */
    public function getGrantor() {
        return $this->grantor;
    }

    /**
     * Set procurator
     *
     * @param \Core\Entity\People $procurator
     * @return PeopleProcurator
     */
    public function setProcurator(\Core\Entity\People $procurator = null) {
        $this->procurator = $procurator;

        return $this;
    }

    /**
     * Get procurator
     *
     * @return \Core\Entity\People 
     */
    public function getProcurator() {
        return $this->procurator;
    }

    /**
     * Set muniment_signature
     *
     * @param \Core\Entity\MunimentSignature $muniment_signature
     * @return PeopleProcurator
     */
    public function setMunimentSignature(\Core\Entity\MunimentSignature $muniment_signature = null) {
        $this->muniment_signature = $muniment_signature;

        return $this;
    }

    /**
     * Get muniment_signature
     *
     * @return \Core\Entity\MunimentSignature 
     */
    public function getMunimentSignature() {
        return $this->muniment_signature;
    }

}
