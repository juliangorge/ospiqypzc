<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="clinical_histories", indexes={
    * @ORM\Index(name="dni", columns={"dni"}),
    * @ORM\Index(name="professional_id", columns={"professional_id"})
* })
*/
class ClinicalHistory
{
    /**
    * @ORM\Id
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /** @ORM\Column(name="file_number", type="string", nullable=true) */
    protected $file_number;
    
    /** @ORM\Column(name="dni", type="string", nullable=false) */
    protected $dni;

    /** @ORM\Column(name="fullname", type="string", nullable=false) */
    protected $fullname;
    
    /** @ORM\Column(name="diagnose", type="text", nullable=true) */
    protected $diagnose;
    
    /** @ORM\Column(name="observations", type="text", nullable=true) */
    protected $observations;
    
    /** @ORM\Column(name="treatment", type="text", nullable=true) */
    protected $treatment;
    
    /** @ORM\Column(name="professional_id", type="integer", nullable=false) */
    protected $professional_id;
    
    /** @ORM\Column(name="date_created", type="datetime", nullable=false) */
    protected $date_created;

    /** @ORM\Column(name="date", type="datetime", nullable=false) */
    protected $date;

    /** @ORM\Column(name="document_id", type="string", unique=true, nullable=true) */
    protected $document_id;

    public function getArrayCopy(){
        return [
            'id' => $this->id,
            'file_number' => $this->file_number,
            'dni' => $this->dni,
            'fullname' => $this->fullname,
            'diagnose' => $this->diagnose,
            'observations' => $this->observations,
            'treatment' => $this->treatment,
            'professional_id' => $this->professional_id,
            'date_created' => $this->date_created->format('Y-m-d'),
            'date' => $this->date->format('Y-m-d\TH:i:s'),
            'document_id' => $this->document_id
        ];
    }

    public function initialize(array $array){
        $this->file_number = $array['file_number'];
        $this->dni = $array['dni'];
        $this->fullname = $array['fullname'];
        $this->diagnose = $array['diagnose'];
        $this->observations = $array['observations'];
        $this->treatment = $array['treatment'];
        $this->professional_id = $array['professional_id'];
        $this->date_created = new \DateTime();
        $this->date = new \DateTime($array['date']);
    }

    public function exchangeArray(array $array){
        $this->file_number = $array['file_number'];
        $this->dni = $array['dni'];
        $this->fullname = $array['fullname'];
        $this->diagnose = $array['diagnose'];
        $this->observations = $array['observations'];
        $this->treatment = $array['treatment'];
        $this->professional_id = $array['professional_id'];
        $this->date = new \DateTime($array['date']);
    }

    public function toFirebase(){
        return [
            'file_number' => $this->file_number,
            'dni' => $this->dni,
            'affiliate_name' => $this->fullname,
            'diagnose' => $this->diagnose,
            'observations' => $this->observations,
            'treatment' => $this->treatment,
            'professional_id' => $this->professional_id,
            'date_created' => $this->date_created->format('d/m/Y'),
            'date' => $this->date->format('d/m/Y'),
            'hour' => $this->date->format('H:i')
        ];
    }

    public function getId(){ return $this->id; }
    public function getFileNumber(){ return $this->file_number; }
    public function getDni(){ return $this->dni; }
    public function getFullname(){ return $this->fullname; }
    public function getDiagnose(){ return $this->diagnose; }
    public function getObservations(){ return $this->observations; }
    public function getTreatment(){ return $this->treatment; }
    public function getProfessionalId(){ return $this->professional_id; }
    public function getDateCreated(){ return $this->date_created; }
    public function getDate(){ return $this->date; }
    public function getDocumentId(){ return $this->document_id; }

    public function setFileNumber($v){ $this->file_number = $v; }
    public function setDni($v){ $this->dni = $v; }
    public function setFullname($v){ $this->fullname = $v; }
    public function setDiagnose($v){ $this->diagnose = $v; }
    public function setObservations($v){ $this->observations = $v; }
    public function setTreatment($v){ $this->treatment = $v; }
    public function setProfessionalId($v){ $this->professional_id = $v; }
    public function setDateCreated($v){ $this->date_created = $v; }
    public function setDate($v){ $this->date = $v; }
    public function setDocumentId($v){ $this->document_id = $v; }
}