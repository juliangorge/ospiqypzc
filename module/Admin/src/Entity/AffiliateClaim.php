<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="affiliates_claims")
*/
class AffiliateClaim
{
    /**
    * @ORM\Id
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /** @ORM\Column(name="claim_id", type="string", nullable=false) */
    protected $claim_id;

    /** @ORM\Column(name="detail", type="string", nullable=false) */
    protected $detail;

    /** @ORM\Column(name="sector", type="string", nullable=false) */
    protected $sector;

    /** @ORM\Column(name="type", type="string", nullable=false) */
    protected $type;

    /** @ORM\Column(name="detail_answer", type="text", nullable=true) */    
    protected $detail_answer;

    /** @ORM\Column(name="date_answer", type="datetime", nullable=true) */    
    protected $date_answer;

    /** @ORM\Column(name="document_id", type="string", unique=true, nullable=true) */
    protected $document_id;

    public function getArrayCopy(){
        return [
            'id' => $this->id,
            'claim_id' => $this->claim_id,
            'detail' => $this->detail,
            'sector' => $this->sector,
            'type' => $this->type,
            'detail_answer' => $this->detail_answer,
            'date_answer' => $this->date_answer,
            'document_id' => $this->document_id
        ];
    }

    public function initialize(array $array){
        $this->claim_id = $array['claim_id'];
        $this->detail = $array['detail'];
        $this->sector = $array['sector'];
        $this->type = $array['type'];
        $this->detail_answer = $array['detail_answer'];
        $this->date_answer = $array['date_answer'];
    }

    public function exchangeArray(array $array){
        $this->claim_id = $array['claim_id'];
        $this->detail = $array['detail'];
        $this->sector = $array['sector'];
        $this->type = $array['type'];
        $this->detail_answer = $array['detail_answer'];
        $this->date_answer = $array['date_answer'];
    }

    public function toFirebase(){
        return [
            'detail_answer' => $this->detail_answer,
            'date_answer' => $this->date_answer->format('d/m/Y H:i'),
        ];
    }

    public function getId(){ return $this->id; }
    public function getClaimId(){ return $this->claim_id; }
    public function getDetail(){ return $this->detail; }
    public function getSector(){ return $this->sector; }
    public function getType(){ return $this->type; }
    public function getDetailAnswer(){ return $this->detail_answer; }
    public function getDateAnswer(){ return $this->date_answer; }
    public function getDocumentId(){ return $this->document_id; }

    public function setClaimId($v){ $this->claim_id = $v; }
    public function setDetail($v){ $this->detail = $v; }
    public function setSector($v){ $this->sector = $v; }
    public function setType($v){ $this->type = $v; }
    public function setDetailAnswer($v){ $this->detail_answer = $v; }
    public function setDateAnswer($v){ $this->date_answer = $v; }
    public function setDocumentId($v){ $this->document_id = $v; }
}