<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="affiliates_claims", indexes={
    * @ORM\Index(name="dni", columns={"dni"}),
    * @ORM\Index(name="user_id", columns={"user_id"})
* })
*/
class AffiliatesClaims
{
    /**
    * @ORM\Id
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /** @ORM\Column(name="claim_id", type="string", nullable=false) */
    protected $claim_id;

    /** @ORM\Column(name="title", type="text", nullable=false) */
    protected $title;

    /** @ORM\Column(name="details", type="text", nullable=false) */
    protected $details;

    /** @ORM\Column(name="details_answer", type="text", nullable=true) */    
    protected $details_answer;

    /** @ORM\Column(name="date_answer", type="datetime", nullable=true) */    
    protected $date_answer;

    /** @ORM\Column(name="date_created", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"}) */
    protected $date_created;

    /** @ORM\Column(name="status", type="integer", nullable=false, options={"default": 0}) */
    protected $status;

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
            'details' => $this->details,
            'details_answer' => $this->details_answer,
            'date_answer' => $this->date_answer,
            'date_created' => $this->date_created,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'dni' => $this->dni,
            'document_id' => $this->document_id
        ];
    }

    public function initialize(array $array){
        $this->claim_id = $array['claim_id'];
        $this->title = $array['title'];
        $this->details = $array['details'];
        $this->details_answer = $array['details_answer'];
        $this->date_answer = $array['date_answer'];
        $this->date_created = new \DateTime();
        $this->status = 0;
        $this->user_id = $array['user_id'];
        $this->dni = $array['dni'];
    }

    public function exchangeArray(array $array){
        $this->details_answer = $array['details_answer'];
        $this->date_answer = new \DateTime();
        $this->status = 1;
        $this->user_id = $array['user_id'];
    }

    public function fromFirebase(array $array){
        $this->claim_id = $array['id'];
        $this->title = $array['title'];
        $this->details = $array['detail'];
        $this->details_answer = $array['response'];
        $this->date_answer = $array['response_date'] == '' ? NULL : \DateTime::createFromFormat('d/m/Y', $array['response_date']);
        $this->date_created = new \DateTime();
        $this->status = $array['response_date'] != '';
        $this->user_id = 1;
        $this->dni = $array['affiliate_dni'];
        $this->document_id = $array['document_id'];
    }

    public function toFirebase(){
        return [
            'response' => $this->details_answer,
            'response_date' => $this->date_answer->format('d/m/Y'),
        ];
    }

    public function getId(){ return $this->id; }
    public function getClaimId(){ return $this->claim_id; }
    public function getTitle(){ return $this->title; }
    public function getDetails(){ return $this->details; }
    public function getDetailsAnswer(){ return $this->details_answer; }
    public function getDateAnswer(){ return $this->date_answer; }
    public function getDateCreated(){ return $this->date_created; }
    public function getStatus(){ return $this->status; }
    public function getDni(){ return $this->dni; }
    public function getUserId(){ return $this->user_id; }
    public function getDocumentId(){ return $this->document_id; }

    public function setClaimId($v){ $this->claim_id = $v; }
    public function setTitle($v){ $this->title = $v; }
    public function setDetails($v){ $this->details = $v; }
    public function setDetailsAnswer($v){ $this->details_answer = $v; }
    public function setDateAnswer($v){ $this->date_answer = $v; }
    public function setDateCreated($v){ $this->date_created = $v; }
    public function setStatus($v){ $this->status = $v; }
    public function setDni($v){ $this->dni = $v; }
    public function setUserId($v){ $this->user_id = $v; }
    public function setDocumentId($v){ $this->document_id = $v; }
}