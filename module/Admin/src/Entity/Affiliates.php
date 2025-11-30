<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="affiliates", indexes={
 * @ORM\Index(name="affiliate_number", columns={"affiliate_number"}),
 * @ORM\Index(name="is_active", columns={"is_active"}),
 * @ORM\Index(name="affiliate_type", columns={"affiliate_type"})
 * })
 */
class Affiliates
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

    /** @ORM\Column(name="birthday", type="date", nullable=false) */
    protected $birthday;

    /** @ORM\Column(name="location", type="string", nullable=false) */
    protected $location;

    /** @ORM\Column(name="phone_number", type="string", nullable=true) */
    protected $phone_number;

    /** @ORM\Column(name="photo_url", type="text", nullable=true) */
    protected $photo_url;

    /** @ORM\Column(name="is_active", type="boolean", nullable=false) */
    protected $is_active;

    /** @ORM\Column(name="affiliate_number", type="string", nullable=true) */
    protected $affiliate_number;

    /** @ORM\Column(name="document_id", type="string", unique=true, nullable=true) */
    protected $document_id;

    /** @ORM\Column(name="token", type="text", nullable=true) */
    protected $token;

    // Sólo en OSPIQYPZC
    /** @ORM\Column(name="region_id", type="integer", nullable=false) */
    protected $region_id;

    /** @ORM\Column(name="affiliate_type", type="integer", nullable=true) */
    protected $affiliate_type;

    /** @ORM\Column(name="credential_number", type="string", nullable=false) */
    protected $credential_number;

    public function getArrayCopy()
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'dni' => $this->dni,
            'email' => $this->email,
            'birthday' => $this->birthday->format('Y-m-d'),
            'location' => $this->location,
            'phone_number' => $this->phone_number,
            'is_active' => (int) $this->is_active,
            'photo_url' => $this->photo_url,
            'affiliate_number' => $this->affiliate_number,
            'token' => $this->token,
            'document_id' => $this->document_id,

            // Sólo en OSPIQYPZC
            'region_id' => $this->region_id,
            'affiliate_type' => $this->affiliate_type,
            'credential_number' => $this->credential_number,
        ];
    }

    public function initialize(array $array)
    {
        $this->first_name = $array['first_name'];
        $this->last_name = $array['last_name'];
        $this->dni = $array['dni'];
        $this->email = $array['email'];
        $this->birthday = new \DateTime($array['birthday']);
        $this->location = $array['location'];
        $this->phone_number = $array['phone_number'];
        $this->token = '';
        $this->is_active = $array['is_active'];

        // Sólo en OSPIQYPZC
        $this->region_id = $array['region_id'];
        $this->affiliate_type = $array['affiliate_type'];
        $this->credential_number = $array['credential_number'];
        $this->affiliate_number = strval($array['affiliate_type']) . '00';
    }

    public function exchangeArray(array $array)
    {
        $this->first_name = $array['first_name'];
        $this->last_name = $array['last_name'];
        $this->dni = $array['dni'];
        $this->email = $array['email'];
        $this->birthday = new \DateTime($array['birthday']);
        $this->location = $array['location'];
        $this->phone_number = $array['phone_number'];
        $this->is_active = $array['is_active'];

        // Sólo en OSPIQYPZC
        $this->region_id = $array['region_id'];
        $this->affiliate_type = $array['affiliate_type'];
        $this->credential_number = $array['credential_number'];
        $this->affiliate_number = strval($array['affiliate_type']) . '00';
    }

    public function fromImport(array $array)
    {
        $birthday = \DateTime::createFromFormat('d/m/Y', $array['birthday']);

        $this->first_name = $array['first_name'];
        $this->last_name = $array['last_name'];
        $this->dni = $array['dni'];
        $this->email = $array['email'];
        $this->birthday = $birthday;
        $this->location = $array['location'];
        $this->phone_number = $array['phone_number'];
        $this->region_id = $array['region_id'];
        $this->is_active = true;

        // Sólo en OSPIQYPZC
        $this->region_id = $array['region_id'];
        $this->affiliate_type = $array['affiliate_type'];
        $this->credential_number = $array['credential_number'];
        $this->affiliate_number = strval($array['affiliate_type']) . '00';
    }

    public function toAffiliateDni()
    {
        return [
            'name' => $this->getFullName(),
            'dni' => $this->dni
        ];
    }

    public function toFirebase($new_affiliate = false)
    {
        $array = [
            'name' => $this->getFullName(),
            'dni' => strval($this->dni),
            'email' => ($this->email == NULL ? '' : $this->email),
            'birthday' => $this->birthday->format('d/m/Y'),
            'location' => ($this->location == NULL ? '' : $this->location),
            'phone_number' => ($this->phone_number == NULL ? '' : $this->phone_number),
            'active_user' => boolval($this->is_active),
            'affiliate_number' => $this->affiliate_number,

            // Sólo en OSPIQYPZC
            'region_id' => $this->getRegionName(),
            'affiliate_type' => $this->affiliate_type,
            'credential_number' => $this->credential_number,
        ];

        if ($new_affiliate) {
            // $array['is_data_validated'] = false;
            $array['token'] = '';
            $array['photo_url'] = '';
        } else {
            if ($this->photo_url != NULL && $this->photo_url != '') {
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
    public function getBirthday()
    {
        return $this->birthday;
    }
    public function getLocation()
    {
        return $this->location;
    }
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }
    public function getPhotoUrl()
    {
        return $this->photo_url;
    }
    public function getIsActive()
    {
        return $this->is_active;
    }
    public function getAffiliateNumber()
    {
        return $this->affiliate_number;
    }
    public function getToken()
    {
        return $this->token;
    }
    public function getDocumentId()
    {
        return $this->document_id;
    }

    // Sólo en OSPIQYPZC
    public function getAffiliateType()
    {
        return $this->affiliate_type;
    }
    public function getCredentialNumber()
    {
        return $this->credential_number;
    }
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
    public function setBirthday($v)
    {
        $this->birthday = $v;
    }
    public function setLocation($v)
    {
        $this->location = $v;
    }
    public function setPhoneNumber($v)
    {
        $this->phone_number = $v;
    }
    public function setPhotoUrl($v)
    {
        $this->photo_url = $v;
    }
    public function setIsActive($v)
    {
        $this->is_active = $v;
    }
    public function setAffiliateNumber($v)
    {
        $this->affiliate_number = $v;
    }
    public function setToken($v)
    {
        $this->token = $v;
    }
    public function setDocumentId($v)
    {
        $this->document_id = $v;
    }

    // Sólo en OSPIQYPZC   
    public function setAffiliateType($v)
    {
        $this->affiliate_type = $v;
    }
    public function setCredentialNumber($v)
    {
        $this->credential_number = $v;
    }
    public function setRegionId($v)
    {
        $this->region_id = $v;
    }
}
