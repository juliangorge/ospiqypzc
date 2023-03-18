<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="specialties")
*/
class Specialty
{
    /**
    * @ORM\Id
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /** @ORM\Column(name="name", type="string", nullable=false) */
    protected $name;

    /** @ORM\Column(name="document_id", type="string", unique=true, nullable=true) */
    protected $document_id;

    public function getArrayCopy(){
        return [
            'id' => $this->id,
            'name' => $this->name,
            'document_id' => $this->document_id
        ];
    }

    public function __construct(array $array){
        $this->name = $array['name'];
    }

    public function exchangeArray(array $array){
        $this->name = $array['name'];
    }

    public function toFirebase(){
        return [
            'type' => $this->name
        ];
    }

    public function getId(){ return $this->id; }
    public function getName(){ return $this->name; }
    public function getDocumentId(){ return $this->document_id; }

    public function setName($v){ $this->name = $v; }
    public function setDocumentId($v){ $this->document_id = $v; }
}