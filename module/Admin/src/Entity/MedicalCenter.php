<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @ORM\Entity
* @ORM\Table(name="medical_centers")
*/
class MedicalCenter
{
    /**
    * @ORM\Id
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /** @ORM\Column(name="name", type="string", nullable=false) */
    protected $name;

    /**
    * Many Professional have Many Specialties.
    * @ORM\ManyToMany(targetEntity="Admin\Entity\Specialty")
    * @ORM\JoinTable(name="specialties_by_medical_center",
    *      joinColumns={@ORM\JoinColumn(name="medical_center_id", referencedColumnName="id")},
    *      inverseJoinColumns={@ORM\JoinColumn(name="specialty_id", referencedColumnName="id", unique=false)}
    * )
    * @var Collection<int, Admin\Entity\Specialty>
    */
    protected Collection $specialties;

    /** @ORM\Column(name="document_id", type="string", unique=true, nullable=true) */
    protected $document_id;

    public function getArrayCopy(){
        return [
            'id' => $this->id,
            'name' => $this->name,
            'document_id' => $this->document_id
        ];
    }

    public function __construct(array $array){
        $this->name = $array['name'];
    }

    public function exchangeArray(array $array){
        $this->name = $array['name'];
    }

    public function toFirebase(){
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public function getId(){ return $this->id; }
    public function getName(){ return $this->name; }
    public function getDocumentId(){ return $this->document_id; }

    public function hasSpecialties() { return !$this->specialties->isEmpty(); }
    public function getSpecialties() { return $this->specialties; }
    public function getSpecialtiesArray(){
        $array = [];
        foreach($this->specialties as $item){
            $array[] = [
                'id' => $item->getId(),
                'name' => $item->getName()
            ];
        }
        return $array;
    }

    public function hasSpecialty(int $specialty_id){
        $found = false;
        foreach($this->specialties as $item){
            if($item->getId() == $specialty_id){
                $found = true;
                break;
            }
        }

        return $found;
    }

    public function setName($v){ $this->name = $v; }
    public function addSpecialties($array){ 
        $this->specialties = new ArrayCollection();
        foreach($array as $item){
            $this->specialties->add($item);
        }
    }
    public function setDocumentId($v){ $this->document_id = $v; }
}