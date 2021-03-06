<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TranslateMenu
 *
 * @ORM\Table(name="translate_menu", uniqueConstraints={@ORM\UniqueConstraint(name="lang_id", columns={"lang_id", "menu_id"})}, indexes={@ORM\Index(name="menu_id", columns={"menu_id"}), @ORM\Index(name="IDX_54F12A45B213FA4", columns={"lang_id"})})
 * @ORM\Entity
 */
class TranslateMenu
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
     * @var \Entity\Menu
     *
     * @ORM\ManyToOne(targetEntity="Entity\Menu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="menu_id", referencedColumnName="id")
     * })
     */
    private $menu;


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
     * @return TranslateMenu
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
     * @return TranslateMenu
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
     * Set menu
     *
     * @param \Entity\Menu $menu
     * @return TranslateMenu
     */
    public function setMenu(\Entity\Menu $menu = null)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return \Entity\Menu 
     */
    public function getMenu()
    {
        return $this->menu;
    }
}
