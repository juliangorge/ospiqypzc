<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="affiliates_family", indexes={
    * @ORM\Index(name="affiliate_dni", columns={"affiliate_dni"}),
    * @ORM\Index(name="type_of_family_member_id", columns={"type_of_family_member_id"})
* })
*/
class AffiliateFamily
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

    /** @ORM\Column(name="email", type="string", nullable=true) */
    protected $email;

    /** @ORM\Column(name="affiliate_dni", type="string", length=10, unique=false, nullable=false) */
    protected $affiliate_dni;

    /** @ORM\Column(name="type_of_family_member_id", type="integer", nullable=false) */
    protected $type_of_family_member_id;

    /** @ORM\Column(name="phone_number", type="string", nullable=true) */
    protected $phone_number;

    /** @ORM\Column(name="birthday", type="date", nullable=false) */
    protected $birthday;

    /** @ORM\Column(name="photo_url", type="string", nullable=true) */
    protected $photo_url;

    /** @ORM\Column(name="document_id", type="string", unique=true, nullable=true) */
    protected $document_id;

    // Custom: OSPIQYPZC
    /** @ORM\Column(name="region_id", type="integer", nullable=true) */
    protected $region_id;
    // Custom: OSPIQYPZC

    /** @ORM\Column(name="is_active", type="boolean", nullable=false) */
    protected $is_active;

    public function getArrayCopy(){
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'dni' => $this->dni,
            'email' => $this->email,
            'affiliate_dni' => $this->affiliate_dni,
            'type_of_family_member_id' => $this->type_of_family_member_id,
            'phone_number' => $this->phone_number,
            'birthday' => $this->birthday,
            'photo_url' => $this->photo_url,
            'region_id' => $this->region_id, // Custom
            'document_id' => $this->document_id,
        ];
    }

    public function initialize(array $array){
        $this->firstname = $array['firstname'];
        $this->lastname = $array['lastname'];
        $this->dni = $array['dni'];
        $this->email = $array['email'];
        $this->affiliate_dni = $array['affiliate_dni'];
        $this->type_of_family_member_id = $array['type_of_family_member_id'];
        $this->type_of_family_member = $array['type_of_family_member'];
        $this->phone_number = $array['phone_number'];
        $this->birthday = new \DateTime($array['birthday']);
        $this->region_id = $array['region_id'];
        $this->is_active = true;
    }

    public function exchangeArray(array $array){
        $this->firstname = $array['firstname'];
        $this->lastname = $array['lastname'];
        $this->dni = $array['dni'];
        $this->email = $array['email'];
        $this->affiliate_dni = $array['affiliate_dni'];
        $this->type_of_family_member_id = $array['type_of_family_member_id'];
        $this->phone_number = $array['phone_number'];
        $this->birthday = new \DateTime($array['birthday']);
        $this->region_id = $array['region_id'];
        $this->is_active = true;
    }

    public function fromImport(array $array){
        $birthday = \DateTime::createFromFormat('d/m/Y', $array['birthday']);

        $this->firstname = $array['firstname'];
        $this->lastname = $array['lastname'];
        $this->dni = $array['dni'];
        $this->email = $array['email'];
        $this->birthday = $birthday;
        $this->affiliate_dni = $array['affiliate_dni'];
        $this->type_of_family_member_id = $array['type_of_family_member_id'];
        $this->phone_number = $array['phone_number'];
        $this->region_id = $array['region_id'];
        $this->is_active = true;
    }

    public function toFirebase(){
        return [
            'name' => $this->firstname . ' ' . $this->lastname,
            'dni' => $this->dni,
            'email' => ($this->email == NULL ? '' : $this->email),
            'birthday' => $this->birthday->format('d/m/Y'),
            'phone_number' => ($this->phone_number == NULL ? '' : $this->phone_number),
            'photo_url' => ($this->photo_url == NULL ? '' : $this->photo_url),
            'type_of_family_member_id' => $this->type_of_family_member_id,
            'affiliate_dni' => $this->affiliate_dni,
            'region' => $this->region_id == 1 ? 'Buenos Aires' : 'Entre Ríos' // Custom
        ];
    }

    public function getId(){ return $this->id; }
    public function getFirstname() { return $this->firstname; }
    public function getLastname() { return $this->lastname; }
    public function getDni() { return $this->dni; }
    public function getEmail() { return $this->email; }
    public function getAffiliateDni() { return $this->affiliate_dni; }
    public function getTypeOfFamilyMemberId() { return $this->type_of_family_member_id; }
    public function getPhoneNumber() { return $this->phone_number; }
    public function getBirthday() { return $this->birthday; }
    public function getPhotoUrl() { return $this->photo_url; }
    public function getRegionId(){ return $this->region_id; } // Custom
    public function getIsActive(){ return $this->is_active; }
    public function getDocumentId() { return $this->document_id; }

    public function setFirstname($v) { $this->firstname = $v; }
    public function setLastname($v) { $this->lastname = $v; }
    public function setDni($v) { $this->dni = $v; }
    public function setEmail($v) { $this->email = $v; }
    public function setAffiliateDni($v) { $this->affiliate_dni = $v; }
    public function setTypeOfFamilyMemberId($v) { $this->type_of_family_member_id = $v; }
    public function setPhoneNumber($v) { $this->phone_number = $v; }
    public function setBirthday($v) { $this->birthday = $v; }
    public function setPhotoUrl($v) { $this->photo_url = $v; }
    public function setRegionId($v){ $this->region_id = $v; } // Custom
    public function setIsActive($v){ $this->is_active = $v; }
    public function setDocumentId($v) { $this->document_id = $v; }
}