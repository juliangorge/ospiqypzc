<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="affiliates", indexes={
    * @ORM\Index(name="is_active", columns={"is_active"}),
    * @ORM\Index(name="affiliate_type", columns={"affiliate_type"})
* })
*/
class Affiliate
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

    /** @ORM\Column(name="birthday", type="date", nullable=false) */
    protected $birthday;

    /** @ORM\Column(name="location", type="string", nullable=false) */
    protected $location;

    /** @ORM\Column(name="phone_number", type="string", nullable=true) */
    protected $phone_number;

    /** @ORM\Column(name="photo_url", type="string", nullable=true) */
    protected $photo_url;

    /** @ORM\Column(name="is_active", type="boolean", nullable=false) */
    protected $is_active;

    /** @ORM\Column(name="affiliate_type", type="integer", nullable=true) */
    protected $affiliate_type;

    /** @ORM\Column(name="document_id", type="string", unique=true, nullable=true) */
    protected $document_id;

    // Custom: OSPIQYPZC
    /** @ORM\Column(name="region_id", type="integer", nullable=true) */
    protected $region_id;
    // Custom: OSPIQYPZC

    public function getArrayCopy(){
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'dni' => $this->dni,
            'email' => $this->email,
            'birthday' => $this->birthday->format('Y-m-d'),
            'location' => $this->location,
            'phone_number' => $this->phone_number,
            'is_active' => $this->is_active,
            'photo_url' => $this->photo_url,
            'affiliate_type' => $this->affiliate_type,
            'region_id' => $this->region_id, // Custom
            'document_id' => $this->document_id,
        ];
    }

    public function initialize(array $array){
        $this->firstname = $array['firstname'];
        $this->lastname = $array['lastname'];
        $this->dni = $array['dni'];
        $this->email = $array['email'];
        $this->birthday = new \DateTime($array['birthday']);
        $this->location = $array['location'];
        $this->phone_number = $array['phone_number'];
        $this->affiliate_type = $array['affiliate_type'];
        $this->region_id = $array['region_id']; // Custom
        $this->is_active = $array['is_active'];
    }

    public function exchangeArray(array $array){
        $this->firstname = $array['firstname'];
        $this->lastname = $array['lastname'];
        $this->dni = $array['dni'];
        $this->email = $array['email'];
        $this->birthday = new \DateTime($array['birthday']);
        $this->location = $array['location'];
        $this->phone_number = $array['phone_number'];
        $this->affiliate_type = $array['affiliate_type'];
        $this->region_id = $array['region_id']; // Custom
        $this->is_active = $array['is_active'];
    }

    public function fromImport(array $array){
        $birthday = \DateTime::createFromFormat('d/m/Y', $array['birthday']);

        $this->firstname = $array['firstname'];
        $this->lastname = $array['lastname'];
        $this->dni = $array['dni'];
        $this->email = $array['email'];
        $this->birthday = $birthday;
        $this->location = $array['location'];
        $this->phone_number = $array['phone_number'];
        $this->affiliate_type = $array['affiliate_type'];
        $this->region_id = $array['region_id']; // Custom
        $this->is_active = $array['is_active'];
    }

    public function toFirebase($update = false){
        $array = [
            'name' => $this->firstname . ' ' . $this->lastname,
            'dni' => $this->dni,
            'email' => ($this->email == NULL ? '' : $this->email),
            'birthday' => $this->birthday->format('d/m/Y'),
            'location' => ($this->location == NULL ? '' : $this->location),
            'phone_number' => ($this->phone_number == NULL ? '' : $this->phone_number),
            'photo_url' => ($this->photo_url == NULL ? '' : $this->photo_url),
            'active_user' => boolval($this->is_active),
            'affiliate_number' => strval($this->affiliate_type) . '00',
            'region_id' => $this->region_id == 1 ? 'Buenos Aires' : 'Entre Ríos' // Custom
        ];

        if($update) $array['is_data_validated'] = false;

        return $array;
    }

    public function getId(){ return $this->id; }
    public function getFirstname(){ return $this->firstname; }
    public function getLastname(){ return $this->lastname; }
    public function getDni(){ return $this->dni; }
    public function getEmail(){ return $this->email; }
    public function getBirthday(){ return $this->birthday; }
    public function getLocation(){ return $this->location; }
    public function getPhoneNumber(){ return $this->phone_number; }
    public function getPhotoUrl(){ return $this->photo_url; }
    public function getIsActive(){ return $this->is_active; }
    public function getAffiliateType(){ return $this->affiliate_type; }
    public function getRegionId(){ return $this->region_id; } // Custom
    public function getDocumentId(){ return $this->document_id; }

    public function setFirstname($v){ $this->firstname = $v; }
    public function setLastname($v){ $this->lastname = $v; }
    public function setDni($v){ $this->dni = $v; }
    public function setEmail($v){ $this->email = $v; }
    public function setBirthday($v){ $this->birthday = $v; }
    public function setLocation($v){ $this->location = $v; }
    public function setPhoneNumber($v){ $this->phone_number = $v; }
    public function setPhotoUrl($v){ $this->photo_url = $v; }
    public function setIsActive($v){ $this->is_active = $v; }
    public function setAffiliateType($v){ $this->affiliate_type = $v; }
    public function setRegionId($v){ $this->region_id = $v; } // Custom
    public function setDocumentId($v){ $this->document_id = $v; }
}