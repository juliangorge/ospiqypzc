<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="affiliates_prescriptions", indexes={
    * @ORM\Index(name="dni", columns={"dni"}),
    * @ORM\Index(name="gender_id", columns={"gender_id"}),
    * @ORM\Index(name="professional_id", columns={"professional_id"})
* })
*/
class AffiliatePrescription
{
    /**
    * @ORM\Id
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /** @ORM\Column(name="dni", type="string", length=10, unique=false, nullable=false) */
    protected $dni;

    /** @ORM\Column(name="fullname", type="string", nullable=false) */
    protected $fullname;

    /** @ORM\Column(name="age", type="string", nullable=false) */
    protected $age;

    /** @ORM\Column(name="gender_id", type="integer", nullable=false) */
    protected $gender_id;

    /** @ORM\Column(name="appointment_date", type="date", nullable=false) */
    protected $appointment_date;

    /** @ORM\Column(name="expiration_date", type="date", nullable=false) */
    protected $expiration_date;

    /** @ORM\Column(name="professional_id", type="integer", nullable=false) */
    protected $professional_id;

    /** @ORM\Column(name="first_medication", type="integer", nullable=false) */
    protected $first_medication;

    /** @ORM\Column(name="second_medication", type="integer", nullable=true) */
    protected $second_medication;

    /** @ORM\Column(name="third_medication", type="integer", nullable=true) */
    protected $third_medication;

    /** @ORM\Column(name="document_id", type="string", unique=true, nullable=true) */
    protected $document_id;

    public function getArrayCopy(){
        return [
            'id' => $this->id,
            'dni' => $this->dni,
            'fullname' => $this->fullname,
            'age' => $this->age,
            'gender_id' => $this->gender_id,
            'appointment_date' => $this->appointment_date,
            'expiration_date' => $this->expiration_date,
            'professional_id' => $this->professional_id,
            'first_medication' => $this->first_medication,
            'second_medication' => $this->second_medication,
            'third_medication' => $this->third_medication,
            'document_id' => $this->document_id,
        ];
    }

    public function initialize(array $array){
        $this->dni = $array['dni'];
        $this->fullname = $array['fullname'];

        $this->age = $array['age'];
        $this->gender_id = $array['gender_id'];

        $appointment_date = new \DateTime($array['appointment_date']);
        $expiration_date = clone $appointment_date;
        $this->appointment_date = $appointment_date;

        $expiration_date->add(new \DateInterval('P1M'));
        $this->expiration_date = $expiration_date;
        $this->professional_id = $array['professional_id'];
        $this->first_medication = $array['first_medication'];
        $this->second_medication = $array['second_medication'] == 0 ? NULL : $array['second_medication'];
        $this->third_medication = $array['third_medication'] == 0 ? NULL : $array['third_medication'];
    }

    public function exchangeArray(array $array){
        $this->dni = $array['dni'];
        $this->fullname = $array['fullname'];

        $this->age = $array['age'];
        $this->gender_id = $array['gender_id'];

        $appointment_date = new \DateTime($array['appointment_date']);
        $expiration_date = clone $appointment_date;
        $this->appointment_date = $appointment_date;

        $expiration_date->add(new \DateInterval('P1M'));
        $this->expiration_date = $expiration_date;
        $this->professional_id = $array['professional_id'];
        $this->first_medication = $array['first_medication'];
        $this->second_medication = $array['second_medication'] == 0 ? NULL : $array['second_medication'];
        $this->third_medication = $array['third_medication'] == 0 ? NULL : $array['third_medication'];
    }

    public function toFirebase(){
        return [
            'id' => $this->id,
            'dni' => $this->dni,
            'affiliate_name' => $this->fullname,
            'age' => $this->age,
            'gender_id' => $this->gender_id,
            'appointment_date' => $this->appointment_date->format('d/m/Y'),
            'expiration_date' => $this->expiration_date->format('d/m/Y'),
            'professional_id' => $this->professional_id,
            'first_medication' => $this->first_medication,
            'second_medication' => $this->second_medication == NULL ? '' : $this->second_medication,
            'third_medication' => $this->third_medication == NULL ? '' : $this->third_medication,
        ];
    }

    public function getId(){ return $this->id; }
    public function getDni(){ return $this->dni; }
    public function getFullname(){ return $this->fullname; }
    public function getAge(){ return $this->age; }
    public function getGenderId(){ return $this->gender_id; }
    public function getAppointmentDate(){ return $this->appointment_date; }
    public function getExpirationDate(){ return $this->expiration_date; }
    public function getProfessionalId(){ return $this->professional_id; }
    public function getFirstMedication(){ return $this->first_medication; }
    public function getSecondMedication(){ return $this->second_medication; }
    public function getThirdMedication(){ return $this->third_medication; }
    public function getDocumentId(){ return $this->document_id; }

    public function setDni($v){ $this->dni = $v; }
    public function setFullname($v){ $this->fullname = $v; }
    public function setAge($v){ $this->age = $v; }
    public function setGenderId($v){ $this->gender_id = $v; }
    public function setAppointmentDate($v){ $this->appointment_date = $v; }
    public function setExpirationDate($v){ $this->expiration_date = $v; }
    public function setProfessionalId($v){ $this->professional_id = $v; }
    public function setFirstMedication($v){ $this->first_medication = $v; }
    public function setSecondMedication($v){ $this->second_medication = $v; }
    public function setThirdMedication($v){ $this->third_medication = $v; }
    public function setDocumentId($v){ $this->document_id = $v; }
}