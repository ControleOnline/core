<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeopleCarrier
 *
 * @ORM\Table(name="people_carrier", uniqueConstraints={@ORM\UniqueConstraint(name="carrier_id", columns={"carrier_id", "company_id"})}, indexes={@ORM\Index(name="company_id", columns={"company_id"}), @ORM\Index(name="IDX_2C6E59348C03F15C", columns={"carrier_id"})})
 * @ORM\Entity
 */
class PeopleCarrier {

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
     * @ORM\ManyToOne(targetEntity="Core\Entity\People", inversedBy="peopleCarrier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     * })
     */
    private $company;

    /**
     * @var \Core\Entity\People
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\People", inversedBy="peopleCompany")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="carrier_id", referencedColumnName="id")
     * })
     * @ORM\OrderBy({"alias" = "ASC"})
     */
    private $carrier;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set company
     *
     * @param \Core\Entity\People $company
     * @return PeopleCarrier
     */
    public function setCompany(\Core\Entity\People $company = null) {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \Core\Entity\People 
     */
    public function getCompany() {
        return $this->company;
    }

    /**
     * Set carrier
     *
     * @param \Core\Entity\People $carrier
     * @return PeopleCarrier
     */
    public function setCarrier(\Core\Entity\People $carrier = null) {
        $this->carrier = $carrier;

        return $this;
    }

    /**
     * Get carrier
     *
     * @return \Core\Entity\People
     */
    public function getCarrier() {
        return $this->carrier;
    }

  

}
