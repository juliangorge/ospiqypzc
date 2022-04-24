<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="vademecum")
*/
class Vademecum
{
    /**
    * @ORM\Id
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /** @ORM\Column(name="drug", type="string", nullable=false) */
    protected $drug;

    /** @ORM\Column(name="presentation", type="string", nullable=false) */
    protected $presentation;

    /** @ORM\Column(name="region_id", type="integer", nullable=false) */
    protected $region_id;

    /** @ORM\Column(name="document_id", type="string", unique=true, nullable=true) */
    protected $document_id;

    public function getArrayCopy(){
        return [
            'id' => $this->id,
            'drug' => $this->drug,
            'presentation' => $this->presentation,
            'region_id' => $this->region_id,
            'document_id' => $this->document_id
        ];
    }

    public function initialize(array $array){
        $this->drug = $array['drug'];
        $this->presentation = $array['presentation'];
        $this->region_id = $array['region_id'];
    }

    public function exchangeArray(array $array){
        $this->drug = $array['drug'];
        $this->presentation = $array['presentation'];
        $this->region_id = $array['region_id'];
    }

    public function toFirebase(){
        return [
            'drug' => $this->drug,
            'presentation' => $this->presentation,
            'region_id' => $this->region_id,
        ];
    }

    public function getId(){ return $this->id; }
    public function getDrug(){ return $this->drug; }
    public function getPresentation(){ return $this->presentation; }
    public function getRegionId(){ return $this->region_id; }
    public function getDocumentId(){ return $this->document_id; }

    public function setDrug($v){ $this->drug = $v; }
    public function setPresentation($v){ $this->presentation = $v; }
    public function setRegionId($v){ $this->region_id = $v; }
    public function setDocumentId($v){ $this->document_id = $v; }
}