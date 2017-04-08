<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeopleCarrier
 *
 * @ORM\Table(name="people_carrier", uniqueConstraints={@ORM\UniqueConstraint(name="carrier_id", columns={"carrier_id", "company_id"})}, indexes={@ORM\Index(name="company_id", columns={"company_id"}), @ORM\Index(name="IDX_2C6E59348C03F15C", columns={"carrier_id"})})
 * @ORM\Entity
 */
class CarrierPeople {

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
     * @ORM\Column(name="company_id", type="integer", nullable=false)
     */
    private $company_id;

    /**
     * @var \Core\Entity\People
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\People")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="carrier_id", referencedColumnName="id")
     * })
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
     * Set company_id
     *
     * @param integer $company_id
     * @return CarrierPeople
     */
    public function setCompanyId($company_id) {
        $this->company_id = $company_id;

        return $this;
    }

    /**
     * Get company_id
     *
     * @return integer 
     */
    public function getCompanyId() {
        return $this->company_id;
    }

    /**
     * Set carrier
     *
     * @param \Core\Entity\People $carrier
     * @return CarrierPeople
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
