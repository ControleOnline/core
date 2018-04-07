<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeopleEmployee
 *
 * @ORM\Table(name="people_employee", uniqueConstraints={@ORM\UniqueConstraint(name="employee_id", columns={"employee_id", "company_id"})}, indexes={@ORM\Index(name="company_id", columns={"company_id"}), @ORM\Index(name="IDX_2C6E59348C03F15C", columns={"employee_id"})})
 * @ORM\Entity
 */
class PeopleEmployee {

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
     * @ORM\ManyToOne(targetEntity="Core\Entity\People", inversedBy="peopleCompany")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     * })
     */
    private $company;

    /**
     * @var \Core\Entity\People
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\People", inversedBy="peopleEmployee")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     * })
     * @ORM\OrderBy({"alias" = "ASC"})
     */
    private $employee;

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
     * @return PeopleEmployee
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
     * Set employee
     *
     * @param \Core\Entity\People $employee
     * @return PeopleEmployee
     */
    public function setEmployee(\Core\Entity\People $employee = null) {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return \Core\Entity\People 
     */
    public function getEmployee() {
        return $this->employee;
    }

}
