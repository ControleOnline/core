<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Address
 *
 * @ORM\Table(name="address", uniqueConstraints={@ORM\UniqueConstraint(name="user_id_3", columns={"people_id", "number", "street_id", "complement"})}, indexes={@ORM\Index(name="user_id_2", columns={"people_id","nickname"}), @ORM\Index(name="user_id", columns={"people_id"}), @ORM\Index(name="cep_id", columns={"street_id"})})
 * @ORM\Entity
 */
class Address {

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
     * @ORM\Column(name="number", type="integer")
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=50, nullable=false)
     */
    private $nickname;

    /**
     * @var string
     *
     * @ORM\Column(name="complement", type="string", length=50, nullable=false)
     */
    private $complement;

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
     * @var \Core\Entity\Street
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Street")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="street_id", referencedColumnName="id")
     * })
     */
    private $street;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float",  nullable=false)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float",  nullable=false)
     */
    private $longitude;

    /**
     * Constructor
     */
    public function __construct() {
        $this->latitude = 0;
        $this->longitude = 0;
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
     * Set number
     *
     * @param integer $number
     * @return Address
     */
    public function setNumber($number) {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber() {
        return $this->number;
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     * @return Address
     */
    public function setNickname($nickname) {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string 
     */
    public function getNickname() {
        return $this->nickname;
    }

    /**
     * Set complement
     *
     * @param string $complement
     * @return Address
     */
    public function setComplement($complement) {
        $this->complement = $complement;

        return $this;
    }

    /**
     * Get complement
     *
     * @return string 
     */
    public function getComplement() {
        return $this->complement;
    }

    /**
     * Set people
     *
     * @param \Core\Entity\People $people
     * @return Address
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
     * Set street
     *
     * @param \Core\Entity\Street $street
     * @return Address
     */
    public function setStreet(\Core\Entity\Street $street = null) {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return \Core\Entity\Street 
     */
    public function getStreet() {
        return $this->street;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Address
     */
    public function setLatitude($latitude) {
        $this->latitude = $latitude? : 0;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Address
     */
    public function setLongitude($longitude) {
        $this->longitude = $longitude? : 0;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude() {
        return $this->longitude;
    }

}
