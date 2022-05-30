<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="affiliates_claims", indexes={
    * @ORM\Index(name="dni", columns={"dni"}),
* })
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

    /** @ORM\Column(name="title", type="string", nullable=false) */
    protected $title;

    /** @ORM\Column(name="detail", type="string", nullable=false) */
    protected $detail;

    /** @ORM\Column(name="detail_answer", type="text", nullable=true) */    
    protected $detail_answer;

    /** @ORM\Column(name="date_answer", type="datetime", nullable=true) */    
    protected $date_answer;

    /** @ORM\Column(name="user_id", type="integer", nullable=true) */    
    protected $user_id;

    /** @ORM\Column(name="dni", type="string", length=10, unique=false, nullable=false) */
    protected $dni;

    /** @ORM\Column(name="document_id", type="string", unique=true, nullable=true) */
    protected $document_id;

    public function getArrayCopy(){
        return [
            'id' => $this->id,
            'claim_id' => $this->claim_id,
            'title' => $this->title,
            'detail' => $this->detail,
            'detail_answer' => $this->detail_answer,
            'date_answer' => $this->date_answer,
            'user_id' => $this->user_id,
            'dni' => $this->dni,
            'document_id' => $this->document_id
        ];
    }

    public function initialize(array $array){
        $this->claim_id = $array['claim_id'];
        $this->title = $array['title'];
        $this->detail = $array['detail'];
        $this->detail_answer = $array['detail_answer'];
        $this->date_answer = $array['date_answer'];
        $this->user_id = $array['user_id'];
        $this->dni = $array['dni'];
    }

    public function exchangeArray(array $array){
        $this->claim_id = $array['claim_id'];
        $this->title = $array['title'];
        $this->detail = $array['detail'];
        $this->detail_answer = $array['detail_answer'];
        //$this->date_answer = new \DateTime();
        $this->user_id = $array['user_id'];
        $this->dni = $array['dni'];
    }

    public function toFirebase(){
        return [
            'response' => $this->detail_answer,
            'response_date' => $this->date_answer->format('d/m/Y'),
        ];
    }

    public function getId(){ return $this->id; }
    public function getClaimId(){ return $this->claim_id; }
    public function getTitle(){ return $this->title; }
    public function getDetail(){ return $this->detail; }
    public function getDetailAnswer(){ return $this->detail_answer; }
    public function getDateAnswer(){ return $this->date_answer; }
    public function getDni(){ return $this->dni; }
    public function getUserId(){ return $this->user_id; }
    public function getDocumentId(){ return $this->document_id; }

    public function setClaimId($v){ $this->claim_id = $v; }
    public function setTitle($v){ $this->title = $v; }
    public function setDetail($v){ $this->detail = $v; }
    public function setDetailAnswer($v){ $this->detail_answer = $v; }
    public function setDateAnswer($v){ $this->date_answer = $v; }
    public function setDni($v){ $this->dni = $v; }
    public function setUserId($v){ $this->user_id = $v; }
    public function setDocumentId($v){ $this->document_id = $v; }
}