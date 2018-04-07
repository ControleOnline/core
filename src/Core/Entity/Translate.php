<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Translate
 *
 * @ORM\Table(name="translate", uniqueConstraints={@ORM\UniqueConstraint(name="lang_id", columns={"lang_id", "translate_key", "people_id"})}, indexes={@ORM\Index(name="translate_key", columns={"translate_key"}),@ORM\Index(name="status", columns={"status"})})
 * @ORM\Entity
 */
class Translate {

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
     * @ORM\Column(name="translate", type="text", nullable=false)
     */
    private $translate;

    /**
     * @var string
     *
     * @ORM\Column(name="translate_key", type="string", length=255, nullable=false)
     */
    private $translate_key;

    /**
     * @var integer
     *
     * @ORM\Column(name="lang_id", type="integer", nullable=false)
     */
    private $lang;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=false)
     */
    private $status;

    public function __construct() {
        /*
         * 1 = 'Waiting for Translation'
         * 2 = 'Automatically Translated'
         * 3 = 'Translation Revised'
         */
        $this->status = '1';
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
     * Set translate
     *
     * @param string $translate
     * @return Translate
     */
    public function setTranslate($translate) {
        $this->translate = $translate;

        return $this;
    }

    /**
     * Get translate
     *
     * @return string 
     */
    public function getTranslate() {
        return $this->translate;
    }

    /**
     * Set lang
     *
     * @param string $lang
     * @return Translate
     */
    public function setLang($lang) {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang
     *
     * @return string 
     */
    public function getLang() {
        return $this->lang;
    }

    /**
     * Set translate_key
     *
     * @param string $translate_key
     * @return Translate
     */
    public function setTranslateKey($translate_key) {
        $this->translate_key = $translate_key;

        return $this;
    }

    /**
     * Get translate_key
     *
     * @return string 
     */
    public function getTranslateKey() {
        return $this->translate_key;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Translate
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Set people
     *
     * @param \Core\Entity\People $people
     * @return PeopleClient
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
