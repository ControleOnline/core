<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeopleConfigKey
 *
 * @ORM\Table(name="config", uniqueConstraints={@ORM\UniqueConstraint(name="people_id", columns={"people_id","config_key"})})
 * @ORM\Entity
 */
class Config {

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
     * @ORM\ManyToOne(targetEntity="Core\Entity\People")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="people_id", referencedColumnName="id")
     * })
     */
    private $people;

    /**
     * @var string
     *
     * @ORM\Column(name="config_key", type="string", length=255, nullable=false)
     */
    private $config_key;

    /**
     * @var string
     *
     * @ORM\Column(name="config_value", type="string", length=15, nullable=false)
     */
    private $config_value;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set people
     *
     * @param \Core\Entity\People $people
     * @return PeopleConfigKey
     */
    public function setPeople(\Core\Entity\People $people = null) {
        $this->people = $people;

        return $this;
    }

    /**
     * Get people
     *
     * @return \Core\Entity\People 
     */
    public function getPeople() {
        return $this->people;
    }

    /**
     * Set config_key
     *
     * @param string config_key
     * @return PeopleConfigKey
     */
    public function setConfigKey($config_key) {
        $this->config_key = $config_key;

        return $this;
    }

    /**
     * Get config_key
     *
     * @return string 
     */
    public function getConfigKey() {
        return $this->config_key;
    }

    /**
     * Set config_value
     *
     * @param string config_value
     * @return PeopleConfigKey
     */
    public function setConfigValue($config_value) {
        $this->config_value = $config_value;

        return $this;
    }

    /**
     * Get config_value
     *
     * @return string 
     */
    public function getConfigValue() {
        return $this->config_value;
    }

}
