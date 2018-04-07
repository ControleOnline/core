<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeopleClient
 *
 * @ORM\Table(name="people_client", uniqueConstraints={@ORM\UniqueConstraint(name="client_id", columns={"client_id", "company_id"})}, indexes={@ORM\Index(name="company_id", columns={"company_id"}), @ORM\Index(name="IDX_2C6E59348C03F15C", columns={"client_id"})})
 * @ORM\Entity
 */
class PeopleClient {

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
     * @ORM\ManyToOne(targetEntity="Core\Entity\People", inversedBy="peopleClient")
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
     *   @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * })
     * @ORM\OrderBy({"alias" = "ASC"})
     */
    private $client;

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
     * @return PeopleClient
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
     * Set client
     *
     * @param \Core\Entity\People $client
     * @return PeopleClient
     */
    public function setClient(\Core\Entity\People $client = null) {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \Core\Entity\People
     */
    public function getClient() {
        return $this->client;
    }

}
