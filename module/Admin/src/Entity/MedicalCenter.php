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

    /** @ORM\Column(name="location", type="string", nullable=false) */
    protected $location;

    /** @ORM\Column(name="address", type="string", nullable=true) */
    protected $address;

    /** @ORM\Column(name="phone", type="string", nullable=true) */
    protected $phone;

    /** @ORM\Column(name="position", type="integer", nullable=true) */
    protected $position;

    /** @ORM\Column(name="time_of_attention", type="string", nullable=true) */
    protected $time_of_attention;

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

    public function getArrayCopy()
    {
        return [
            'id' => $this->id,
            'location' => $this->location,
            'address' => $this->address,
            'phone' => $this->phone,
            'position' => $this->position,
            'time_of_attention' => $this->time_of_attention,
            'document_id' => $this->document_id
        ];
    }

    public function __construct(array $array)
    {
        $this->location = $array['location'] ?? null;
        $this->address = $array['address'] ?? null;
        $this->phone = $array['phone'] ?? null;
        $this->position = $array['position'] ?? null;
        $this->time_of_attention = $array['time_of_attention'] ?? null;
    }

    public function exchangeArray(array $array)
    {
        $this->location = $array['location'] ?? null;
        $this->address = $array['address'] ?? null;
        $this->phone = $array['phone'] ?? null;
        $this->position = $array['position'] ?? null;
        $this->time_of_attention = $array['time_of_attention'] ?? null;
    }

    public function toFirebase()
    {
        return [
            'id' => $this->id,
            'location' => $this->location,
            'address' => $this->address,
            'phone' => $this->phone,
            'position' => $this->position,
            'time_of_attention' => $this->time_of_attention,
        ];
    }

    public function getId()
    {
        return $this->id;
    }
    public function getLocation()
    {
        return $this->location;
    }
    public function getAddress()
    {
        return $this->address;
    }
    public function getPhone()
    {
        return $this->phone;
    }
    public function getPosition()
    {
        return $this->position;
    }
    public function getTimeOfAttention()
    {
        return $this->time_of_attention;
    }
    public function getDocumentId()
    {
        return $this->document_id;
    }

    public function hasSpecialties()
    {
        return !$this->specialties->isEmpty();
    }
    public function getSpecialties()
    {
        return $this->specialties;
    }
    public function getSpecialtiesArray()
    {
        $array = [];
        foreach ($this->specialties as $item) {
            $array[] = [
                'id' => $item->getId(),
                'name' => $item->getName()
            ];
        }
        return $array;
    }

    public function hasSpecialty(int $specialty_id)
    {
        $found = false;
        foreach ($this->specialties as $item) {
            if ($item->getId() == $specialty_id) {
                $found = true;
                break;
            }
        }

        return $found;
    }

    public function setLocation($v)
    {
        $this->location = $v;
    }
    public function setAddress($v)
    {
        $this->address = $v;
    }
    public function setPhone($v)
    {
        $this->phone = $v;
    }
    public function setPosition($v)
    {
        $this->position = $v;
    }
    public function setTimeOfAttention($v)
    {
        $this->time_of_attention = $v;
    }
    public function addSpecialties($array)
    {
        $this->specialties = new ArrayCollection();
        foreach ($array as $item) {
            $this->specialties->add($item);
        }
    }
    public function setDocumentId($v)
    {
        $this->document_id = $v;
    }
}
