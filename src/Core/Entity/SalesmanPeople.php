<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeopleSalesman
 *
 * @ORM\Table(name="people_salesman", uniqueConstraints={@ORM\UniqueConstraint(name="salesman_id", columns={"salesman_id", "company_id"})}, indexes={@ORM\Index(name="company_id", columns={"company_id"}), @ORM\Index(name="IDX_2C6E59348C03F15C", columns={"salesman_id"})})
 * @ORM\Entity
 */
class SalesmanPeople {

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
     *   @ORM\JoinColumn(name="salesman_id", referencedColumnName="id")
     * })
     */
    private $salesman;

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
     * @return SalesmanPeople
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
     * Set salesman
     *
     * @param \Core\Entity\People $salesman
     * @return SalesmanPeople
     */
    public function setSalesman(\Core\Entity\People $salesman = null) {
        $this->salesman = $salesman;

        return $this;
    }

    /**
     * Get salesman
     *
     * @return \Core\Entity\People 
     */
    public function getSalesman() {
        return $this->salesman;
    }

}
