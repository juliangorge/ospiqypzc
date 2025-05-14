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
class AffiliatesFamily
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

    /** @ORM\Column(name="email", type="string", nullable=true) */
    protected $email;

    /** @ORM\Column(name="affiliate_dni", type="string", length=10, unique=false, nullable=false) */
    protected $affiliate_dni;

    /** @ORM\Column(name="credential_number", type="string", nullable=false) */
    protected $credential_number;

    /** @ORM\Column(name="type_of_family_member_id", type="integer", nullable=false) */
    protected $type_of_family_member_id;

    /** @ORM\Column(name="phone_number", type="string", nullable=true) */
    protected $phone_number;

    /** @ORM\Column(name="birthday", type="date", nullable=false) */
    protected $birthday;

    /** @ORM\Column(name="photo_url", type="text", nullable=true) */
    protected $photo_url;

    /** @ORM\Column(name="document_id", type="string", unique=true, nullable=true) */
    protected $document_id;

    /** @ORM\Column(name="is_active", type="boolean", nullable=false) */
    protected $is_active;

    // Sólo en OSPIQYPZC
    /** @ORM\Column(name="region_id", type="integer", nullable=false) */
    protected $region_id;

    public function getArrayCopy()
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'dni' => $this->dni,
            'email' => $this->email,
            'affiliate_dni' => $this->affiliate_dni,
            'credential_number' => $this->credential_number,
            'type_of_family_member_id' => $this->type_of_family_member_id,
            'phone_number' => $this->phone_number,
            'birthday' => $this->birthday,
            'photo_url' => $this->photo_url,
            'document_id' => $this->document_id,

            // Sólo en OSPIQYPZC
            'region_id' => $this->region_id,
        ];
    }

    public function initialize(array $array)
    {
        $this->first_name = $array['first_name'];
        $this->last_name = $array['last_name'];
        $this->dni = $array['dni'];
        $this->email = $array['email'];
        $this->affiliate_dni = $array['affiliate_dni'];
        $this->credential_number = $array['credential_number'];
        $this->type_of_family_member_id = $array['type_of_family_member_id'];
        $this->phone_number = $array['phone_number'];
        $this->birthday = new \DateTime($array['birthday']);
        $this->region_id = $array['region_id'];
        $this->is_active = true;

        // Sólo en OSPIQYPZC
        $this->region_id = $array['region_id'];
    }

    public function exchangeArray(array $array)
    {
        $this->first_name = $array['first_name'];
        $this->last_name = $array['last_name'];
        $this->dni = $array['dni'];
        $this->email = $array['email'];
        $this->affiliate_dni = $array['affiliate_dni'];
        $this->credential_number = $array['credential_number'];
        $this->type_of_family_member_id = $array['type_of_family_member_id'];
        $this->phone_number = $array['phone_number'];
        $this->birthday = new \DateTime($array['birthday']);
        $this->region_id = $array['region_id'];
        $this->is_active = true;

        // Sólo en OSPIQYPZC
        $this->region_id = $array['region_id'];
    }

    public function fromImport(array $array)
    {
        $birthday = \DateTime::createFromFormat('d/m/Y', $array['birthday']);

        $this->first_name = $array['first_name'];
        $this->last_name = $array['last_name'];
        $this->dni = $array['dni'];
        $this->email = $array['email'];
        $this->birthday = $birthday;
        $this->affiliate_dni = $array['affiliate_dni'];
        $this->credential_number = $array['credential_number'];
        $this->type_of_family_member_id = $array['type_of_family_member_id'];
        $this->phone_number = $array['phone_number'];
        $this->region_id = $array['region_id'];
        $this->is_active = true;

        // Sólo en OSPIQYPZC
        $this->region_id = $array['region_id'];
    }

    public function toFirebase($new_relative = false)
    {
        $array = [
            'name' => $this->getFullName(),
            'dni' => strval($this->dni),
            'email' => ($this->email == NULL ? '' : $this->email),
            'birthday' => $this->birthday->format('d/m/Y'),
            'phone_number' => ($this->phone_number == NULL ? '' : $this->phone_number),
            'type_of_family_member_id' => $this->type_of_family_member_id,
            'affiliate_dni' => $this->affiliate_dni,
            'credential_number' => $this->credential_number,

            // Sólo en OSPIQYPZC
            'region_id' => $this->getRegionName(),
        ];

        if ($new_relative) {
            $array['photo_url'] = '';
        } else {
            if ($this->photo_url != NULL) {
                $array['photo_url'] = $this->photo_url;
            } else {
                $array['photo_url'] = '';
            }
        }
        return $array;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getFirstName()
    {
        return $this->first_name;
    }
    public function getLastName()
    {
        return $this->last_name;
    }
    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    public function getDni()
    {
        return $this->dni;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getAffiliateDni()
    {
        return $this->affiliate_dni;
    }
    public function getCredentialNumber()
    {
        return $this->credential_number;
    }
    public function getTypeOfFamilyMemberId()
    {
        return $this->type_of_family_member_id;
    }
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }
    public function getBirthday()
    {
        return $this->birthday;
    }
    public function getPhotoUrl()
    {
        return $this->photo_url;
    }
    public function getIsActive()
    {
        return $this->is_active;
    }
    public function getDocumentId()
    {
        return $this->document_id;
    }

    // Sólo en OSPIQYPZC
    public function getRegionId()
    {
        return $this->region_id;
    }
    public function getRegionName()
    {
        return ($this->region_id == 1 ? 'Buenos Aires' : 'Entre Ríos');
    }

    public function setFirstName($v)
    {
        $this->first_name = $v;
    }
    public function setLastName($v)
    {
        $this->last_name = $v;
    }
    public function setDni($v)
    {
        $this->dni = $v;
    }
    public function setEmail($v)
    {
        $this->email = $v;
    }
    public function setAffiliateDni($v)
    {
        $this->affiliate_dni = $v;
    }
    public function setCredentialNumber($v)
    {
        $this->credential_number = $v;
    }
    public function setTypeOfFamilyMemberId($v)
    {
        $this->type_of_family_member_id = $v;
    }
    public function setPhoneNumber($v)
    {
        $this->phone_number = $v;
    }
    public function setBirthday($v)
    {
        $this->birthday = $v;
    }
    public function setPhotoUrl($v)
    {
        $this->photo_url = $v;
    }
    public function setIsActive($v)
    {
        $this->is_active = $v;
    }
    public function setDocumentId($v)
    {
        $this->document_id = $v;
    }

    // Sólo en OSPIQYPZC
    public function setRegionId($v)
    {
        $this->region_id = $v;
    }
}
