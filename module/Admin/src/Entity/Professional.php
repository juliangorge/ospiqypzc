<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="professionals", indexes={
    * @ORM\Index(name="specialty_id", columns={"specialty_id"}),
    * @ORM\Index(name="type_of_attention_id", columns={"type_of_attention_id"})
* })
*/
class Professional
{
    /**
    * @ORM\Id
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /** @ORM\Column(name="firstname", type="string", nullable=false) */
    protected $firstname;

    /** @ORM\Column(name="lastname", type="string", nullable=false) */
    protected $lastname;

    /** @ORM\Column(name="dni", type="string", length=10, unique=true, nullable=false) */
    protected $dni;

    /** @ORM\Column(name="specialty_id", type="integer", nullable=false) */
    protected $specialty_id;

    /** @ORM\Column(name="type_of_attention_id", type="integer", nullable=false) */
    protected $type_of_attention_id;

    /** @ORM\Column(name="document_id", type="string", unique=true, nullable=true) */
    protected $document_id;

    public function getArrayCopy(){
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'dni' => $this->dni,
            'specialty_id' => $this->specialty_id,
            'type_of_attention_id' => $this->type_of_attention_id,
            'document_id' => $this->document_id
        ];
    }

    public function initialize(array $array){
        $this->firstname = $array['firstname'];
        $this->lastname = $array['lastname'];
        $this->dni = $array['dni'];
        $this->specialty_id = $array['specialty_id'];
        $this->type_of_attention_id = $array['type_of_attention_id'];
    }

    public function exchangeArray(array $array){
        $this->firstname = $array['firstname'];
        $this->lastname = $array['lastname'];
        $this->dni = $array['dni'];
        $this->specialty_id = $array['specialty_id'];
        $this->type_of_attention_id = $array['type_of_attention_id'];
    }

    public function toFirebase(){
        return [
            'name' => $this->lastname . ', ' .  $this->firstname,
            'dni' => $this->dni,
            'specialty_id' => $this->specialty_id,
            'type_of_attention_id' => $this->type_of_attention_id
        ];
    }

    public function __toString(){
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getId(){ return $this->id; }
    public function getFirstname(){ return $this->firstname; }
    public function getLastname(){ return $this->lastname; }
    public function getDni(){ return $this->dni; }
    public function getSpecialtyId(){ return $this->specialty_id; }
    public function getTypeOfAttentionId(){ return $this->type_of_attention_id; }
    public function getDocumentId(){ return $this->document_id; }

    public function setFirstname($v){ $this->firstname = $v; }
    public function setLastname($v){ $this->lastname = $v; }
    public function setDni($v){ $this->dni = $v; }
    public function setSpecialtyId($v){ $this->specialty_id = $v; }
    public function setTypeOfAttentionId($v){ $this->type_of_attention_id = $v; }
    public function setDocumentId($v){ $this->document_id = $v; }
}