<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MenuPage
 *
 * @ORM\Table(name="menu_page", indexes={@ORM\Index(name="menuPage_id", columns={"page_id"}), @ORM\Index(name="menu_id", columns={"menu_id"})})
 * @ORM\Entity
 */
class MenuPage
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
     * @ORM\Column(name="uri", type="string", length=50, nullable=false)
     */
    private $uri;

    /**
     * @var \Core\Entity\MenuPage
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\MenuPage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     * })
     */
    private $page;

    /**
     * @var \Core\Entity\Menu
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Menu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="menu_id", referencedColumnName="id")
     * })
     */
    private $menu;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\MenuPage", mappedBy="page")
     */
    private $menuPage;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\TranslateMenuPage", mappedBy="menuPage")
     */
    private $translateMenuPage;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->menuPage = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translateMenuPage = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set uri
     *
     * @param string $uri
     * @return MenuPage
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Get uri
     *
     * @return string 
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set page
     *
     * @param \Core\Entity\MenuPage $page
     * @return MenuPage
     */
    public function setPage(\Core\Entity\MenuPage $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \Core\Entity\MenuPage 
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set menu
     *
     * @param \Core\Entity\Menu $menu
     * @return MenuPage
     */
    public function setMenu(\Core\Entity\Menu $menu = null)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return \Core\Entity\Menu 
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Add menuPage
     *
     * @param \Core\Entity\MenuPage $menuPage
     * @return MenuPage
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
     * Add translateMenuPage
     *
     * @param \Core\Entity\TranslateMenuPage $translateMenuPage
     * @return MenuPage
     */
    public function addTranslateMenuPage(\Core\Entity\TranslateMenuPage $translateMenuPage)
    {
        $this->translateMenuPage[] = $translateMenuPage;

        return $this;
    }

    /**
     * Remove translateMenuPage
     *
     * @param \Core\Entity\TranslateMenuPage $translateMenuPage
     */
    public function removeTranslateMenuPage(\Core\Entity\TranslateMenuPage $translateMenuPage)
    {
        $this->translateMenuPage->removeElement($translateMenuPage);
    }

    /**
     * Get translateMenuPage
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTranslateMenuPage()
    {
        return $this->translateMenuPage;
    }
}
