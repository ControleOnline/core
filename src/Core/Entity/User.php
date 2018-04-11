<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="users", uniqueConstraints={@ORM\UniqueConstraint(name="user_name", columns={"username"})}, indexes={@ORM\Index(name="people_id", columns={"people_id"})})
 * @ORM\Entity
 */
class User {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=50, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=60, nullable=false)
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="api_key", type="string", length=60, nullable=false)
     */
    private $api_key;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\Log", mappedBy="user")
     */
    private $log;

    /**
     * Constructor
     */
    public function __construct() {
        $this->log = new \Doctrine\Common\Collections\ArrayCollection();
        $this->api_key = md5(microtime());
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set username
     *
     * @param string $api_key
     * @return User
     */
    public function generateApiKey() {
        $this->api_key = md5(microtime());

        return $this;
    }

    /**
     * Get api_key
     *
     * @return string 
     */
    public function getApiKey() {
        return $this->api_key;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return User
     */
    public function setHash($hash) {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash() {
        return $this->hash;
    }

    /**
     * Set people
     *
     * @param \Core\Entity\People $people
     * @return User
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
     * Add log
     *
     * @param \Core\Entity\Log $log
     * @return User
     */
    public function addLog(\Core\Entity\Log $log) {
        $this->log[] = $log;

        return $this;
    }

    /**
     * Remove log
     *
     * @param \Core\Entity\Log $log
     */
    public function removeLog(\Core\Entity\Log $log) {
        $this->log->removeElement($log);
    }

    /**
     * Get log
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLog() {
        return $this->log;
    }

}
