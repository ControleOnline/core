<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * People
 *
 * @ORM\Table(name="people", uniqueConstraints={@ORM\UniqueConstraint(name="image_id", columns={"image_id"}),@ORM\UniqueConstraint(name="alternative_image", columns={"alternative_image"})})
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
     * @var boolean
     *
     * @ORM\Column(name="enable", type="boolean",  nullable=false)
     */
    private $enable;

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
     * @var float
     *
     * @ORM\Column(name="billing", type="float", nullable=false)
     */
    private $billing;

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
     * @var \Core\Entity\Image
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Image")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="alternative_image", referencedColumnName="id")
     * })
     */
    private $alternative_image;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\Address", mappedBy="people")
     * @ORM\OrderBy({"nickname" = "ASC"})
     */
    private $address;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\Document", mappedBy="people")
     */
    private $document;

    /**
     * @var \Core\Entity\Language
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * })
     */
    private $language;

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
     * @ORM\OneToMany(targetEntity="Core\Entity\PeopleDomain", mappedBy="people")     
     */
    private $peopleDomain;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\PeopleProvider", mappedBy="company")
     * @ORM\OrderBy({"provider" = "ASC"})
     */
    private $peopleProvider;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\PeopleSalesman", mappedBy="company")
     * @ORM\OrderBy({"salesman" = "ASC"})
     */
    private $peopleSalesman;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\PeopleClient", mappedBy="company")
     * @ORM\OrderBy({"client" = "ASC"})
     */
    private $peopleClient;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\PeopleEmployee", mappedBy="employee")
     * @ORM\OrderBy({"employee" = "ASC"})
     */
    private $peopleEmployee;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\PeopleCarrier", mappedBy="company")
     * @ORM\OrderBy({"carrier" = "ASC"})
     */
    private $peopleCarrier;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\PeopleEmployee", mappedBy="company")
     * @ORM\OrderBy({"company" = "ASC"})
     */
    private $peopleCompany;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\User", mappedBy="people")
     * @ORM\OrderBy({"username" = "ASC"})
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\DeliveryRestrictionMaterial
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\DeliveryRestrictionMaterial", mappedBy="carrier")
     */
    private $delivery_restriction_material;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\Order", mappedBy="client")
     * @ORM\OrderBy({"alter_date" = "DESC"})
     */
    private $purchasing_order;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\Order", mappedBy="provider")
     * @ORM\OrderBy({"alter_date" = "DESC"})     
     */
    private $sale_order;

    /**
     * Constructor
     */
    public function __construct() {
        $this->enable = 0;
        $this->billing = 0;
        $this->address = new \Doctrine\Common\Collections\ArrayCollection();
        $this->document = new \Doctrine\Common\Collections\ArrayCollection();
        $this->email = new \Doctrine\Common\Collections\ArrayCollection();
        $this->peopleClient = new \Doctrine\Common\Collections\ArrayCollection();
        $this->peopleProvider = new \Doctrine\Common\Collections\ArrayCollection();
        $this->peopleSalesman = new \Doctrine\Common\Collections\ArrayCollection();
        $this->peopleEmployee = new \Doctrine\Common\Collections\ArrayCollection();
        $this->peopleDomain = new \Doctrine\Common\Collections\ArrayCollection();
        $this->peopleCarrier = new \Doctrine\Common\Collections\ArrayCollection();
        $this->peopleCompany = new \Doctrine\Common\Collections\ArrayCollection();
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
        $this->phone = new \Doctrine\Common\Collections\ArrayCollection();
        $this->delivery_restriction_material = new \Doctrine\Common\Collections\ArrayCollection();
        $this->purchasing_order = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sale_order = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get enable
     *
     * @return boolean
     */
    public function getEnabled() {
        return $this->enable;
    }

    /**
     * Set enable
     *
     * @param boolean $enable
     * @return People
     */
    public function setEnabled($enable) {
        $this->enable = $enable ? : 0;

        return $this;
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
     * Set alternative_image
     *
     * @param \Core\Entity\Image $alternative_image
     * @return People
     */
    public function setAlternativeImage(\Core\Entity\Image $alternative_image = null) {
        $this->alternative_image = $alternative_image;

        return $this;
    }

    /**
     * Get alternative_image
     *
     * @return \Core\Entity\Image 
     */
    public function getAlternativeImage() {
        return $this->alternative_image;
    }

    /**
     * Set language
     *
     * @param \Core\Entity\Language $language
     * @return People
     */
    public function setLanguage(\Core\Entity\Language $language = null) {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return \Core\Entity\Language
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * Add address
     *
     * @param \Core\Entity\Address $address
     * @return People
     */
    public function addAddress(\Core\Entity\Address $address) {
        $this->address[] = $address;

        return $this;
    }

    /**
     * Remove address
     *
     * @param \Core\Entity\Address $address
     */
    public function removeAddress(\Core\Entity\Address $address) {
        $this->address->removeElement($address);
    }

    /**
     * Get address
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAddress() {
        return $this->address;
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
     * Add peopleProvider
     *
     * @param \Core\Entity\PeopleProvider $peopleProvider
     * @return People
     */
    public function addPeopleProvider(\Core\Entity\PeopleProvider $peopleProvider) {
        $this->peopleProvider[] = $peopleProvider;

        return $this;
    }

    /**
     * Remove peopleProvider
     *
     * @param \Core\Entity\PeopleProvider $peopleProvider
     */
    public function removePeopleProvider(\Core\Entity\PeopleProvider $peopleProvider) {
        $this->peopleProvider->removeElement($peopleProvider);
    }

    /**
     * Get peopleProvider
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPeopleProvider() {
        return $this->peopleProvider;
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
     * Add peopleCarrier
     *
     * @param \Core\Entity\peopleCarrier $peopleCarrier
     * @return People
     */
    public function addPeopleCarrier(\Core\Entity\PeopleCarrier $peopleCarrier) {
        $this->peopleCarrier[] = $peopleCarrier;

        return $this;
    }

    /**
     * Remove peopleCarrier
     *
     * @param \Core\Entity\peopleCarrier $peopleCarrier
     */
    public function removePeopleCarrier(\Core\Entity\PeopleCarrier $peopleCarrier) {
        $this->peopleCarrier->removeElement($peopleCarrier);
    }

    /**
     * Get peopleCarrier
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPeopleCarrier() {
        return $this->peopleCarrier;
    }

    /**
     * Add peopleCompany
     *
     * @param \Core\Entity\PeopleEmployee $peopleCompany
     * @return People
     */
    public function addPeopleCompany(\Core\Entity\PeopleEmployee $peopleCompany) {
        $this->peopleCompany[] = $peopleCompany;

        return $this;
    }

    /**
     * Remove peopleCompany
     *
     * @param \Core\Entity\PeopleCompany $peopleCompany
     */
    public function removePeopleCompany(\Core\Entity\PeopleEmployee $peopleCompany) {
        $this->peopleCompany->removeElement($peopleCompany);
    }

    /**
     * Get peopleCompany
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPeopleCompany() {
        return $this->peopleCompany;
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

    /**
     * Add PeopleDomain
     *
     * @param \Core\Entity\PeopleDomain $peopleDomain
     * @return People
     */
    public function addPeopleDomain(\Core\Entity\PeopleDomain $peopleDomain) {
        $this->peopleDomain[] = $peopleDomain;

        return $this;
    }

    /**
     * Remove PeopleDomain
     *
     * @param \Core\Entity\PeopleDomain $peopleDomain
     */
    public function removePeopleDomain(\Core\Entity\PeopleDomain $peopleDomain) {
        $this->peopleDomain->removeElement($peopleDomain);
    }

    /**
     * Get PeopleDomain
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPeopleDomain() {
        return $this->peopleDomain;
    }

    /**
     * Add peopleSalesman
     *
     * @param \Core\Entity\PeopleSalesman $peopleSalesman
     * @return People
     */
    public function addPeopleSalesman(\Core\Entity\PeopleSalesman $peopleSalesman) {
        $this->peopleSalesman[] = $peopleSalesman;

        return $this;
    }

    /**
     * Remove peopleSalesman
     *
     * @param \Core\Entity\PeopleSalesman $peopleSalesman
     */
    public function removePeopleSalesman(\Core\Entity\PeopleSalesman $peopleSalesman) {
        $this->peopleSalesman->removeElement($peopleSalesman);
    }

    /**
     * Get peopleSalesman
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPeopleSalesman() {
        return $this->peopleSalesman;
    }

    /**
     * Add delivery_restriction_material
     *
     * @param \Core\Entity\RestrictionMaterial $delivery_restriction_material
     * @return Cep
     */
    public function addRestrictionMaterial(\Core\Entity\DeliveryRestrictionMaterial $delivery_restriction_material) {
        $this->delivery_restriction_material[] = $delivery_restriction_material;

        return $this;
    }

    /**
     * Remove delivery_restriction_material
     *
     * @param \Core\Entity\RestrictionMaterial $delivery_restriction_material
     */
    public function removeRestrictionMaterial(\Core\Entity\DeliveryRestrictionMaterial $delivery_restriction_material) {
        $this->delivery_restriction_material->removeElement($delivery_restriction_material);
    }

    /**
     * Get delivery_restriction_material
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRestrictionMaterial() {
        return $this->delivery_restriction_material;
    }

    /**
     * Set billing
     *
     * @param string $billing
     * @return People
     */
    public function setBilling($billing) {
        $this->billing = $billing;

        return $this;
    }

    /**
     * Get billing
     *
     * @return float 
     */
    public function getBilling() {
        return $this->billing;
    }

    /**
     * Add sale_order
     *
     * @param \Core\Entity\Order $sale_order
     * @return People
     */
    public function addSaleOrder(\Core\Entity\Order $sale_order) {
        $this->sale_order[] = $sale_order;

        return $this;
    }

    /**
     * Remove sale_order
     *
     * @param \Core\Entity\Order $sale_order
     */
    public function removeSaleOrder(\Core\Entity\Order $sale_order) {
        $this->sale_order->removeElement($sale_order);
    }

    /**
     * Get sale_order
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSaleOrder() {
        return $this->sale_order;
    }

    /**
     * Add purchasing_order
     *
     * @param \Core\Entity\Order $purchasing_order
     * @return People
     */
    public function addPurchasingOrder(\Core\Entity\Order $purchasing_order) {
        $this->purchasing_order[] = $purchasing_order;

        return $this;
    }

    /**
     * Remove purchasing_order
     *
     * @param \Core\Entity\Order $purchasing_order
     */
    public function removePurchasingOrder(\Core\Entity\Order $purchasing_order) {
        $this->purchasing_order->removeElement($purchasing_order);
    }

    /**
     * Get purchasing_order
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPurchasingOrder() {
        return $this->purchasing_order;
    }

}
