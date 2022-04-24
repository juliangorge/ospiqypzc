<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="affiliates_types")
*/
class AffiliateType
{
    /**
    * @ORM\Id
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /** @ORM\Column(name="name", type="string", nullable=false) */
    protected $name;

    /** @ORM\Column(name="value", type="integer", nullable=false) */
    protected $value;

    /** @ORM\Column(name="document_id", type="string", unique=true, nullable=true) */
    protected $document_id;

    public function getArrayCopy(){
        return [
            'id' => $this->id,
            'name' => $this->name,
            'value' => $this->value,
            'document_id' => $this->document_id
        ];
    }

    public function initialize(array $array){
        $this->name = $array['name'];
        $this->value = $array['value'];
    }

    public function exchangeArray(array $array){
        $this->name = $array['name'];
        $this->value = $array['value'];
    }

    public function toFirebase(){
        return [
            'name' => $this->name,
            'value' => $this->name,
        ];
    }

    public function getId(){ return $this->id; }
    public function getName(){ return $this->name; }
    public function getValue(){ return $this->value; }
    public function getDocumentId(){ return $this->document_id; }

    public function setName($v){ $this->name = $v; }
    public function setValue($v){ $this->value = $v; }
    public function setDocumentId($v){ $this->document_id = $v; }
}