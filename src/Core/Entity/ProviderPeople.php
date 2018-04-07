<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeopleProvider
 *
 * @ORM\Table(name="people_provider", uniqueConstraints={@ORM\UniqueConstraint(name="provider_id", columns={"provider_id", "company_id"})}, indexes={@ORM\Index(name="company_id", columns={"company_id"}), @ORM\Index(name="IDX_2C6E59348C03F15C", columns={"provider_id"})})
 * @ORM\Entity
 */
class ProviderPeople {

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
     *   @ORM\JoinColumn(name="provider_id", referencedColumnName="id")
     * })
     */
    private $provider;

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
     * @return ProviderPeople
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
     * Set provider
     *
     * @param \Core\Entity\People $provider
     * @return ProviderPeople
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

}
