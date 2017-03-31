<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TranslateMenuPage
 *
 * @ORM\Table(name="translate_menu_page", uniqueConstraints={@ORM\UniqueConstraint(name="lang_id", columns={"lang_id", "menu_page_id"})}, indexes={@ORM\Index(name="menu_page_id", columns={"menu_page_id"}), @ORM\Index(name="IDX_7C62DFEFB213FA4", columns={"lang_id"})})
 * @ORM\Entity
 */
class TranslateMenuPage
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
     * @var string
     *
     * @ORM\Column(name="translate", type="string", length=50, nullable=false)
     */
    private $translate;

    /**
     * @var \Entity\Language
     *
     * @ORM\ManyToOne(targetEntity="Entity\Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="lang_id", referencedColumnName="id")
     * })
     */
    private $lang;

    /**
     * @var \Entity\MenuPage
     *
     * @ORM\ManyToOne(targetEntity="Entity\MenuPage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="menu_page_id", referencedColumnName="id")
     * })
     */
    private $menuPage;


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
     * Set translate
     *
     * @param string $translate
     * @return TranslateMenuPage
     */
    public function setTranslate($translate)
    {
        $this->translate = $translate;

        return $this;
    }

    /**
     * Get translate
     *
     * @return string 
     */
    public function getTranslate()
    {
        return $this->translate;
    }

    /**
     * Set lang
     *
     * @param \Entity\Language $lang
     * @return TranslateMenuPage
     */
    public function setLang(\Entity\Language $lang = null)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang
     *
     * @return \Entity\Language 
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set menuPage
     *
     * @param \Entity\MenuPage $menuPage
     * @return TranslateMenuPage
     */
    public function setMenuPage(\Entity\MenuPage $menuPage = null)
    {
        $this->menuPage = $menuPage;

        return $this;
    }

    /**
     * Get menuPage
     *
     * @return \Entity\MenuPage 
     */
    public function getMenuPage()
    {
        return $this->menuPage;
    }
}
