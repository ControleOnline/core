<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeopleOrder
 *
 * @ORM\Table(name="people_order", indexes={@ORM\Index(name="people_client_id", columns={"people_client_id"})})
 * @ORM\Entity
 */
class PeopleOrder
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="order_value", type="float", precision=10, scale=0, nullable=false)
     */
    private $orderValue;

    /**
     * @var \Core\Entity\PeopleClient
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\PeopleClient")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="people_client_id", referencedColumnName="id")
     * })
     */
    private $peopleClient;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set orderValue
     *
     * @param float $orderValue
     * @return PeopleOrder
     */
    public function setOrderValue($orderValue)
    {
        $this->orderValue = $orderValue;

        return $this;
    }

    /**
     * Get orderValue
     *
     * @return float 
     */
    public function getOrderValue()
    {
        return $this->orderValue;
    }

    /**
     * Set peopleClient
     *
     * @param \Core\Entity\PeopleClient $peopleClient
     * @return PeopleOrder
     */
    public function setPeopleClient(\Core\Entity\PeopleClient $peopleClient = null)
    {
        $this->peopleClient = $peopleClient;

        return $this;
    }

    /**
     * Get peopleClient
     *
     * @return \Core\Entity\PeopleClient 
     */
    public function getPeopleClient()
    {
        return $this->peopleClient;
    }
}
