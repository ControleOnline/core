<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeopleSalesman
 *
 * @ORM\Table(name="people_salesman", uniqueConstraints={@ORM\UniqueConstraint(name="salesman_id", columns={"salesman_id", "company_id"})}, indexes={@ORM\Index(name="company_id", columns={"company_id"}), @ORM\Index(name="IDX_2C6E59348C03F15C", columns={"salesman_id"})})
 * @ORM\Entity
 */
class PeopleSalesman {

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
     * @ORM\ManyToOne(targetEntity="Core\Entity\People", inversedBy="peopleSalesman")
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
     *   @ORM\JoinColumn(name="salesman_id", referencedColumnName="id")
     * })
     * @ORM\OrderBy({"alias" = "ASC"})
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
     * Set company
     *
     * @param \Core\Entity\People $company
     * @return PeopleSalesman
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
     * Set salesman
     *
     * @param \Core\Entity\Salesman $salesman
     * @return PeopleSalesman
     */
    public function setSalesman(\Core\Entity\Salesman $salesman = null) {
        $this->salesman = $salesman;

        return $this;
    }

    /**
     * Get salesman
     *
     * @return \Core\Entity\Salesman
     */
    public function getSalesman() {
        return $this->salesman;
    }

}
