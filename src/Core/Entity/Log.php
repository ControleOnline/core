<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Log
 *
 * @ORM\Table(name="log", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class Log
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
     * @var integer
     *
     * @ORM\Column(name="row_id", type="integer", nullable=false)
     */
    private $rowId;

    /**
     * @var string
     *
     * @ORM\Column(name="table", type="string", length=50, nullable=false)
     */
    private $table;

    /**
     * @var string
     *
     * @ORM\Column(name="old", type="text", nullable=false)
     */
    private $old;

    /**
     * @var string
     *
     * @ORM\Column(name="new", type="text", nullable=false)
     */
    private $new;

    /**
     * @var \Core\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;


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
     * Set rowId
     *
     * @param integer $rowId
     * @return Log
     */
    public function setRowId($rowId)
    {
        $this->rowId = $rowId;

        return $this;
    }

    /**
     * Get rowId
     *
     * @return integer 
     */
    public function getRowId()
    {
        return $this->rowId;
    }

    /**
     * Set table
     *
     * @param string $table
     * @return Log
     */
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Get table
     *
     * @return string 
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Set old
     *
     * @param string $old
     * @return Log
     */
    public function setOld($old)
    {
        $this->old = $old;

        return $this;
    }

    /**
     * Get old
     *
     * @return string 
     */
    public function getOld()
    {
        return $this->old;
    }

    /**
     * Set new
     *
     * @param string $new
     * @return Log
     */
    public function setNew($new)
    {
        $this->new = $new;

        return $this;
    }

    /**
     * Get new
     *
     * @return string 
     */
    public function getNew()
    {
        return $this->new;
    }

    /**
     * Set user
     *
     * @param \Core\Entity\User $user
     * @return Log
     */
    public function setUser(\Core\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Core\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
