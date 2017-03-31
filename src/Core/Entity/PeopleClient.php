<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeopleClient
 *
 * @ORM\Table(name="people_client", uniqueConstraints={@ORM\UniqueConstraint(name="client_id", columns={"client_id", "provider_id"})}, indexes={@ORM\Index(name="provider_id", columns={"provider_id"}), @ORM\Index(name="IDX_B15326BF19EB6921", columns={"client_id"})})
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
     * @var integer
     *
     * @ORM\Column(name="provider_id", type="integer", nullable=false)
     */
    private $provider;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\People", mappedBy="peopleClient")
     */
    private $client;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\PeopleOrder", mappedBy="peopleClient")
     */
    private $peopleOrder;

    /**
     * Constructor
     */
    public function __construct() {
        $this->peopleOrder = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set provider
     *
     * @param \Core\Entity\People $provider
     * @return PeopleClient
     */
    public function setProvider(\Core\Entity\People $provider = null) {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     *
     * @return \Core\Entity\People 
     */
    public function getProvider() {
        return $this->provider;
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

    /**
     * Add peopleOrder
     *
     * @param \Core\Entity\PeopleOrder $peopleOrder
     * @return PeopleClient
     */
    public function addPeopleOrder(\Core\Entity\PeopleOrder $peopleOrder) {
        $this->peopleOrder[] = $peopleOrder;

        return $this;
    }

    /**
     * Remove peopleOrder
     *
     * @param \Core\Entity\PeopleOrder $peopleOrder
     */
    public function removePeopleOrder(\Core\Entity\PeopleOrder $peopleOrder) {
        $this->peopleOrder->removeElement($peopleOrder);
    }

    /**
     * Get peopleOrder
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPeopleOrder() {
        return $this->peopleOrder;
    }

}
