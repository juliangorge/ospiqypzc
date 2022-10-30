<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="professionals", indexes={
    * @ORM\Index(name="specialty_id", columns={"specialty_id"}),
    * @ORM\Index(name="type_of_medical_attention_id", columns={"type_of_medical_attention_id"})
* })
*/
class Professionals
{
    /**
    * @ORM\Id
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /** @ORM\Column(name="first_name", type="string", nullable=false) */
    protected $first_name;

    /** @ORM\Column(name="last_name", type="string", nullable=false) */
    protected $last_name;

    /** @ORM\Column(name="dni", type="string", length=10, unique=true, nullable=false) */
    protected $dni;

    /**
    * @ORM\ManyToOne(targetEntity="Admin\Entity\Specialties")
    * @ORM\JoinColumn(name="specialty_id", referencedColumnName="id")
    */
    protected $specialty_id;

    /**
    * @ORM\ManyToOne(targetEntity="Admin\Entity\TypesOfMedicalAttention")
    * @ORM\JoinColumn(name="type_of_medical_attention_id", referencedColumnName="id")
    */
    protected $type_of_medical_attention_id;

    /** @ORM\Column(name="registration", type="string", nullable=true) */    
    protected $registration;

    /** @ORM\Column(name="college", type="string", nullable=true) */    
    protected $college;

    /** @ORM\Column(name="cuit", type="string", nullable=false) */
    protected $cuit;

    /** @ORM\Column(name="phone_number", type="string", nullable=false) */
    protected $phone_number;

    /** @ORM\Column(name="email", type="string", nullable=false) */
    protected $email;

    /** @ORM\Column(name="is_active", type="boolean", nullable=false) */
    protected $is_active;

    /** @ORM\Column(name="document_id", type="string", unique=true, nullable=true) */
    protected $document_id;

    public function getArrayCopy(){
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'dni' => $this->dni,
            'specialty_id' => $this->specialty_id,
            'type_of_medical_attention_id' => $this->type_of_medical_attention_id,
            'registration' => $this->registration,
            'college' => $this->college,
            'cuit' => $this->cuit,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'is_active' => $this->is_active,
            'document_id' => $this->document_id
        ];
    }

    public function initialize(array $array){
        $this->first_name = $array['first_name'];
        $this->last_name = $array['last_name'];
        $this->dni = $array['dni'];
        $this->specialty_id = $array['specialty_id'];
        $this->type_of_medical_attention_id = $array['type_of_medical_attention_id'];
        $this->registration = $array['registration'];
        $this->college = $array['college'];
        $this->cuit = $array['cuit'];
        $this->phone_number = $array['phone_number'];
        $this->email = $array['email'];
        $this->is_active = $array['is_active'];
    }

    public function exchangeArray(array $array){
        $this->first_name = $array['first_name'];
        $this->last_name = $array['last_name'];
        $this->dni = $array['dni'];
        $this->specialty_id = $array['specialty_id'];
        $this->type_of_medical_attention_id = $array['type_of_medical_attention_id'];
        $this->registration = $array['registration'];
        $this->college = $array['college'];
        $this->cuit = $array['cuit'];
        $this->phone_number = $array['phone_number'];
        $this->email = $array['email'];
        $this->is_active = $array['is_active'];
    }

    public function toFirebase(){
        return [
            'name' => $this->last_name . ', ' .  $this->first_name,
            'dni' => $this->dni,
            'specialty' => $this->specialty_id->getName(),
            'type_of_attention' => $this->type_of_medical_attention_id->getName()
        ];
    }

    public function __toString(){
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getId(){ return $this->id; }
    public function getFirstName(){ return $this->first_name; }
    public function getLastName(){ return $this->last_name; }
    public function getFullName(){ return $this->last_name . ' ' . $this->first_name; }
    public function getDni(){ return $this->dni; }
    public function getSpecialtyId(){ return $this->specialty_id; }
    public function getTypeOfAttentionId(){ return $this->type_of_medical_attention_id; }
    public function getRegistration(){ return $this->registration; }
    public function getCollege(){ return $this->college; }
    public function getCuit(){ return $this->cuit; }
    public function getPhoneNumber(){ return $this->phone_number; }
    public function getEmail(){ return $this->email; }
    public function getIsActive(){ return $this->is_active; }
    public function getDocumentId(){ return $this->document_id; }

    public function setFirstName($v){ $this->first_name = $v; }
    public function setLastName($v){ $this->last_name = $v; }
    public function setDni($v){ $this->dni = $v; }
    public function setSpecialtyId($v){ $this->specialty_id = $v; }
    public function setTypeOfAttentionId($v){ $this->type_of_medical_attention_id = $v; }
    public function setRegistration($v){ $this->registration = $v; }
    public function setCollege($v){ $this->college = $v; }
    public function setCuit($v){ $this->cuit = $v; }
    public function setPhoneNumber($v){ $this->phone_number = $v; }
    public function setEmail($v){ $this->email = $v; }
    public function setIsActive($v){ $this->is_active = $v; }
    public function setDocumentId($v){ $this->document_id = $v; }
}