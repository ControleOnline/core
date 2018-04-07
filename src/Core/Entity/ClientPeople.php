<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeopleClient
 *
 * @ORM\Table(name="people_client", uniqueConstraints={@ORM\UniqueConstraint(name="client_id", columns={"client_id", "company_id"})}, indexes={@ORM\Index(name="company_id", columns={"company_id"}), @ORM\Index(name="IDX_2C6E59348C03F15C", columns={"client_id"})})
 * @ORM\Entity
 */
class ClientPeople {

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
     *   @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * })
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
     * Set company_id
     *
     * @param integer $company_id
     * @return ClientPeople
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
     * Set client
     *
     * @param \Core\Entity\People $client
     * @return ClientPeople
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
