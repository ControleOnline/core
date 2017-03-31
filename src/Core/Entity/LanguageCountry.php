<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LanguageCountry
 *
 * @ORM\Table(name="language_country", uniqueConstraints={@ORM\UniqueConstraint(name="language_id", columns={"language_id", "country_id"})}, indexes={@ORM\Index(name="country_id", columns={"country_id"}), @ORM\Index(name="IDX_F7BE1E3282F1BAF4", columns={"language_id"})})
 * @ORM\Entity
 */
class LanguageCountry
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
     * @var \Core\Entity\Language
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * })
     */
    private $language;

    /**
     * @var \Core\Entity\Country
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     * })
     */
    private $country;


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
     * Set language
     *
     * @param \Core\Entity\Language $language
     * @return LanguageCountry
     */
    public function setLanguage(\Core\Entity\Language $language = null)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return \Core\Entity\Language 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set country
     *
     * @param \Core\Entity\Country $country
     * @return LanguageCountry
     */
    public function setCountry(\Core\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \Core\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }
}
