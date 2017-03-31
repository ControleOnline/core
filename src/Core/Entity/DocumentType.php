<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DocumentType
 *
 * @ORM\Table(name="document_type")
 * @ORM\Entity
 */
class DocumentType {

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
     * @ORM\Column(name="document_type", type="string", length=50, nullable=false)
     */
    private $documentType;

    /**
     * @var string
     *
     * @ORM\Column(name="people_type", type="string", length=1, nullable=false)
     */
    private $peopleType;

    /**
     * Constructor
     */
    public function __construct() {
        
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
     * Set documentType
     *
     * @param string $documentType
     * @return DocumentType
     */
    public function setDocumentType($documentType) {
        $this->documentType = $documentType;

        return $this;
    }

    /**
     * Get documentType
     *
     * @return string 
     */
    public function getDocumentType() {
        return $this->documentType;
    }

    /**
     * Set peopleType
     *
     * @param string $peopleType
     * @return DocumentType
     */
    public function setPeopleType($peopleType) {
        $this->peopleType = $peopleType;

        return $this;
    }

    /**
     * Get peopleType
     *
     * @return string 
     */
    public function getPeopleType() {
        return $this->peopleType;
    }

}
