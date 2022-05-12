<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="affiliates_authorizations", indexes={
    * @ORM\Index(name="dni", columns={"dni"}),
    * @ORM\Index(name="user_id", columns={"user_id"})
* })
*/
class AffiliateAuthorization
{
    /**
    * @ORM\Id
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /** @ORM\Column(name="dni", type="string", length=10, unique=false, nullable=false) */
    protected $dni;

    /** @ORM\Column(name="user_id", type="integer", nullable=true) */
    protected $user_id;

    /** @ORM\Column(name="authorization_date", type="datetime", nullable=true) */
    protected $authorization_date;

    /** @ORM\Column(name="date_created", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"}) */
    protected $date_created;

    /** @ORM\Column(name="is_approved", type="boolean", nullable=false, options={"default": 0}) */
    protected $is_approved;

    /** @ORM\Column(name="type_of_authorization", type="string", nullable=false) */
    protected $type_of_authorization;

    /** @ORM\Column(name="medical_order_image_url", type="string", nullable=true) */
    protected $medical_order_image_url;

    /** @ORM\Column(name="complementary_studies_image_url", type="string", nullable=true) */
    protected $complementary_studies_image_url;

    /** @ORM\Column(name="document_id", type="string", unique=true, nullable=true) */
    protected $document_id;

    public function getArrayCopy(){
        return [
            'id' => $this->id,
            'dni' => $this->dni,
            'user_id' => $this->user_id,
            'authorization_date' => $this->authorization_date,
            'date_created' => $this->date_created,
            'is_approved' => $this->is_approved,
            'type_of_authorization' => $this->type_of_authorization,
            'medical_order_image_url' => $this->medical_order_image_url,
            'complementary_studies_image_url' => $this->complementary_studies_image_url,
            'document_id' => $this->document_id,
        ];
    }

    public function initialize(array $array){
        $this->dni = $array['dni'];
        $this->user_id = $array['user_id'];
        $this->authorization_date = $array['authorization_date'];
        $this->date_created = new \DateTime();
        $this->is_approved = $array['is_approved'];
        $this->type_of_authorization = $array['type_of_authorization'];
        $this->medical_order_image_url = $array['medical_order_image_url'];
    }

    public function exchangeArray(array $array){
        $this->dni = $array['dni'];
        $this->user_id = $array['user_id'];
        $this->authorization_date = $array['authorization_date'];
        $this->is_approved = $array['is_approved'];
        $this->type_of_authorization = $array['type_of_authorization'];
        $this->complementary_studies_image_url = $array['complementary_studies_image_url'];
    }

    public function authorizing(array $array){
        $this->user_id = $array['user_id'];
        $this->authorization_date = new \DateTime();
        $this->is_approved = boolval($array['is_approved']);
    }

    public function toFirebase(){
        return [
            'authorization_date' => $this->authorization_date == NULL ? '' : $this->authorization_date->format('d/m/Y'),
            'is_approved' => $this->is_approved,
        ];
    }

    public function getId(){ return $this->id; }
    public function getDni(){ return $this->dni; }
    public function getUserID(){ return $this->user_id; }
    public function getAuthorizationDate(){ return $this->authorization_date; }
    public function getDateCreated(){ return $this->date_created; }
    public function getIsApproved(){ return $this->is_approved; }
    public function getTypeOfAuthorization(){ return $this->type_of_authorization; }
    public function getMedicalOrderImageUrl(){ return $this->medical_order_image_url; }
    public function getComplementaryStudiesImageUrl(){ return $this->complementary_studies_image_url; }
    public function getDocumentId(){ return $this->document_id; }

    public function setDni($v){ $this->dni = $v; }
    public function setUserID($v){ $this->user_id = $v; }
    public function setAuthorizationDate($v){ $this->authorization_date = $v; }
    public function setDateCreated($v){ $this->date_created = $v; }
    public function setIsApproved($v){ $this->is_approved = $v; }
    public function setTypeOfAuthorization($v){ $this->type_of_authorization = $v; }
    public function setMedicalOrderImageUrl($v){ $this->medical_order_image_url = $v; }
    public function setComplementaryStudiesImageUrl($v){ $this->complementary_studies_image_url = $v; }
    public function setDocumentId($v){ $this->document_id = $v; }
}