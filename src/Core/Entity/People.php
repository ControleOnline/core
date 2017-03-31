<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * People
 *
 * @ORM\Table(name="people", uniqueConstraints={@ORM\UniqueConstraint(name="image_id", columns={"image_id"})})
 * @ORM\Entity
 */
class People {

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
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=50, nullable=false)
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="people_type", type="string", length=1, nullable=false)
     */
    private $peopleType;

    /**
     * @var \Core\Entity\Image
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Image")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     * })
     */
    private $image;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\Adress", mappedBy="people")
     */
    private $adress;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\Document", mappedBy="people")
     */
    private $document;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\Phone", mappedBy="people")
     */
    private $phone;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\Email", mappedBy="people")
     */
    private $email;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\PeopleClient", mappedBy="client")
     */
    private $peopleClient;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\PeopleEmployee", mappedBy="employee")
     */
    private $peopleEmployee;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\User", mappedBy="people")
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct() {
        $this->adress = new \Doctrine\Common\Collections\ArrayCollection();
        $this->document = new \Doctrine\Common\Collections\ArrayCollection();
        $this->email = new \Doctrine\Common\Collections\ArrayCollection();
        $this->peopleClient = new \Doctrine\Common\Collections\ArrayCollection();
        $this->peopleEmployee = new \Doctrine\Common\Collections\ArrayCollection();
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
        $this->phone = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set people_type
     *
     * @param string $people_type
     * @return People
     */
    public function setPeopleType($people_type) {
        $this->peopleType = $people_type;

        return $this;
    }

    /**
     * Get people_type
     *
     * @return string 
     */
    public function getPeopleType() {
        return $this->peopleType;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return People
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return People
     */
    public function setAlias($alias) {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias() {
        return $this->alias;
    }

    /**
     * Set image
     *
     * @param \Core\Entity\Image $image
     * @return People
     */
    public function setImage(\Core\Entity\Image $image = null) {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Core\Entity\Image 
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Add adress
     *
     * @param \Core\Entity\Adress $adress
     * @return People
     */
    public function addAdress(\Core\Entity\Adress $adress) {
        $this->adress[] = $adress;

        return $this;
    }

    /**
     * Remove adress
     *
     * @param \Core\Entity\Adress $adress
     */
    public function removeAdress(\Core\Entity\Adress $adress) {
        $this->adress->removeElement($adress);
    }

    /**
     * Get adress
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdress() {
        return $this->adress;
    }

    /**
     * Add document
     *
     * @param \Core\Entity\Phone $phone
     * @return People
     */
    public function addPhone(\Core\Entity\Phone $phone) {
        $this->phone[] = $phone;

        return $this;
    }

    /**
     * Remove phone
     *
     * @param \Core\Entity\Phone $phone
     */
    public function removePhone(\Core\Entity\Phone $phone) {
        $this->phone->removeElement($phone);
    }

    /**
     * Get document
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * Add document
     *
     * @param \Core\Entity\Document $document
     * @return People
     */
    public function addDocument(\Core\Entity\Document $document) {
        $this->document[] = $document;

        return $this;
    }

    /**
     * Remove document
     *
     * @param \Core\Entity\Document $document
     */
    public function removeDocument(\Core\Entity\Document $document) {
        $this->document->removeElement($document);
    }

    /**
     * Get document
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDocument() {
        return $this->document;
    }

    /**
     * Add email
     *
     * @param \Core\Entity\Email $email
     * @return People
     */
    public function addEmail(\Core\Entity\Email $email) {
        $this->email[] = $email;

        return $this;
    }

    /**
     * Remove email
     *
     * @param \Core\Entity\Email $email
     */
    public function removeEmail(\Core\Entity\Email $email) {
        $this->email->removeElement($email);
    }

    /**
     * Get email
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Add peopleClient
     *
     * @param \Core\Entity\PeopleClient $peopleClient
     * @return People
     */
    public function addPeopleClient(\Core\Entity\PeopleClient $peopleClient) {
        $this->peopleClient[] = $peopleClient;

        return $this;
    }

    /**
     * Remove peopleClient
     *
     * @param \Core\Entity\PeopleClient $peopleClient
     */
    public function removePeopleClient(\Core\Entity\PeopleClient $peopleClient) {
        $this->peopleClient->removeElement($peopleClient);
    }

    /**
     * Get peopleClient
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPeopleClient() {
        return $this->peopleClient;
    }

    /**
     * Add peopleEmployee
     *
     * @param \Core\Entity\PeopleEmployee $peopleEmployee
     * @return People
     */
    public function addPeopleEmployee(\Core\Entity\PeopleEmployee $peopleEmployee) {
        $this->peopleEmployee[] = $peopleEmployee;

        return $this;
    }

    /**
     * Remove peopleEmployee
     *
     * @param \Core\Entity\PeopleEmployee $peopleEmployee
     */
    public function removePeopleEmployee(\Core\Entity\PeopleEmployee $peopleEmployee) {
        $this->peopleEmployee->removeElement($peopleEmployee);
    }

    /**
     * Get peopleEmployee
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPeopleEmployee() {
        return $this->peopleEmployee;
    }

    /**
     * Add user
     *
     * @param \Core\Entity\User $user
     * @return People
     */
    public function addUser(\Core\Entity\User $user) {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Core\Entity\User $user
     */
    public function removeUser(\Core\Entity\User $user) {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUser() {
        return $this->user;
    }

}
