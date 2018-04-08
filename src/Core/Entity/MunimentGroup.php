<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Muniment
 *
 * @ORM\Table(name="muniment_group", indexes={@ORM\Index(name="people_id", columns={"people_id"})})
 * @ORM\Entity
 */
class MunimentGroup {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Core\Entity\Muniment", mappedBy="muniment_group")
     */
    private $muniment;

    /**
     * @var string
     *
     * @ORM\Column(name="group_name", type="string",  nullable=false)
     */
    private $group_name = '';

    /**
     * @var \Core\Entity\People
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\People")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="people_id", referencedColumnName="id")
     * })
     */
    private $people;

    public function resetId() {
        $this->id = null;
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
     * Get group_name
     *
     * @return string
     */
    public function getGroupName() {
        return $this->group_name;
    }

    /**
     * Set group_name
     *
     * @param string $group_name
     * @return Muniment
     */
    public function setGroupName($group_name = null) {
        $this->group_name = $group_name;
        return $this;
    }

    /**
     * Get muniment
     *
     * @return \Core\Entity\Muniment
     */
    public function getMuniment() {
        return $this->muniment;
    }

    /**
     * Set muniment
     *
     * @param \Core\Entity\Muniment $muniment
     * @return Muniment
     */
    public function setMuniment(Core\Entity\Muniment $muniment = null) {
        $this->muniment = $muniment;
        return $this;
    }

    /**
     * Set people
     *
     * @param \Core\Entity\People $people
     * @return Order
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
