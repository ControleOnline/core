<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Phone
 *
 * @ORM\Table(name="phone", uniqueConstraints={@ORM\UniqueConstraint(name="phone", columns={"phone","ddd","people_id"})}, indexes={@ORM\Index(name="IDX_E7927C743147C936", columns={"people_id"})})
 * @ORM\Entity
 */
class Phone {

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
     * @ORM\Column(name="phone", type="integer", length=10, nullable=false)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="ddd", type="integer", length=2, nullable=false)
     */
    private $ddd;

    /**
     * @var boolean
     *
     * @ORM\Column(name="confirmed", type="boolean", nullable=false)
     */
    private $confirmed = '0';

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
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set ddd
     *
     * @param string $ddd
     * @return Ddd
     */
    public function setDdd($ddd) {
        $this->ddd = $ddd;

        return $this;
    }

    /**
     * Get ddd
     *
     * @return string 
     */
    public function getDdd() {
        return $this->ddd;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Phone
     */
    public function setPhone($phone) {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * Set confirmed
     *
     * @param boolean $confirmed
     * @return Phone
     */
    public function setConfirmed($confirmed) {
        $this->confirmed = $confirmed;

        return $this;
    }

    /**
     * Get confirmed
     *
     * @return boolean 
     */
    public function getConfirmed() {
        return $this->confirmed;
    }

    /**
     * Set people
     *
     * @param \Core\Entity\People $people
     * @return Phone
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

}
