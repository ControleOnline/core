<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menu
 *
 * @ORM\Table(name="menu", uniqueConstraints={@ORM\UniqueConstraint(name="menu", columns={"menu"})})
 * @ORM\Entity
 */
class Menu
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
     * @ORM\Column(name="menu", type="string", length=50, nullable=false)
     */
    private $menu;

    /**
     * @var boolean
     *
     * @ORM\Column(name="locked", type="boolean", nullable=false)
     */
    private $locked;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\MenuPage", mappedBy="menu")
     */
    private $menuPage;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\TranslateMenu", mappedBy="menu")
     */
    private $translateMenu;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->menuPage = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translateMenu = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set menu
     *
     * @param string $menu
     * @return Menu
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return string 
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set locked
     *
     * @param boolean $locked
     * @return Menu
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked
     *
     * @return boolean 
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Add menuPage
     *
     * @param \Core\Entity\MenuPage $menuPage
     * @return Menu
     */
    public function addMenuPage(\Core\Entity\MenuPage $menuPage)
    {
        $this->menuPage[] = $menuPage;

        return $this;
    }

    /**
     * Remove menuPage
     *
     * @param \Core\Entity\MenuPage $menuPage
     */
    public function removeMenuPage(\Core\Entity\MenuPage $menuPage)
    {
        $this->menuPage->removeElement($menuPage);
    }

    /**
     * Get menuPage
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMenuPage()
    {
        return $this->menuPage;
    }

    /**
     * Add translateMenu
     *
     * @param \Core\Entity\TranslateMenu $translateMenu
     * @return Menu
     */
    public function addTranslateMenu(\Core\Entity\TranslateMenu $translateMenu)
    {
        $this->translateMenu[] = $translateMenu;

        return $this;
    }

    /**
     * Remove translateMenu
     *
     * @param \Core\Entity\TranslateMenu $translateMenu
     */
    public function removeTranslateMenu(\Core\Entity\TranslateMenu $translateMenu)
    {
        $this->translateMenu->removeElement($translateMenu);
    }

    /**
     * Get translateMenu
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTranslateMenu()
    {
        return $this->translateMenu;
    }
}
