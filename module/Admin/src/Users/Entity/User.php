<?php

namespace Users\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="\Users\Repository\UserRepository")
 */
class User
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", nullable=false, unique=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", nullable=false, unique=false)
     */
    private $first_name;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", nullable=false, unique=false)
     */
    private $last_name;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", nullable=false, unique=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=false, unique=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="rank_id", type="string", nullable=false, unique=false)
     */
    private $rank_id;

    /**
     * @var string
     *
     * @ORM\Column(name="date_created", type="string", nullable=false, unique=false)
     */
    private $date_created;

    /**
     * @var string
     *
     * @ORM\Column(name="pwd_reset_token", type="string", nullable=false, unique=false)
     */
    private $password_reset_token;

    /**
     * @var string
     *
     * @ORM\Column(name="pwd_reset_token_creation_date", type="string", nullable=false, unique=false)
     */
    private $pwd_reset_token_creation_date;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", nullable=false, unique=false)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="address_id", type="string", nullable=false, unique=false)
     */
    private $address_id;


    /**
     * Get id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set rankId.
     *
     * @param string $rankId
     *
     * @return User
     */
    public function setRankId($rankId)
    {
        $this->rank_id = $rankId;

        return $this;
    }

    /**
     * Get rankId.
     *
     * @return string
     */
    public function getRankId()
    {
        return $this->rank_id;
    }

    /**
     * Set dateCreated.
     *
     * @param string $dateCreated
     *
     * @return User
     */
    public function setDateCreated($dateCreated)
    {
        $this->date_created = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated.
     *
     * @return string
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * Set passwordResetToken.
     *
     * @param string $passwordResetToken
     *
     * @return User
     */
    public function setPasswordResetToken($passwordResetToken)
    {
        $this->password_reset_token = $passwordResetToken;

        return $this;
    }

    /**
     * Get passwordResetToken.
     *
     * @return string
     */
    public function getPasswordResetToken()
    {
        return $this->password_reset_token;
    }

    /**
     * Set pwdResetTokenCreationDate.
     *
     * @param string $pwdResetTokenCreationDate
     *
     * @return User
     */
    public function setPwdResetTokenCreationDate($pwdResetTokenCreationDate)
    {
        $this->pwd_reset_token_creation_date = $pwdResetTokenCreationDate;

        return $this;
    }

    /**
     * Get pwdResetTokenCreationDate.
     *
     * @return string
     */
    public function getPwdResetTokenCreationDate()
    {
        return $this->pwd_reset_token_creation_date;
    }

    /**
     * Set phone.
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set addressId.
     *
     * @param string $addressId
     *
     * @return User
     */
    public function setAddressId($addressId)
    {
        $this->address_id = $addressId;

        return $this;
    }

    /**
     * Get addressId.
     *
     * @return string
     */
    public function getAddressId()
    {
        return $this->address_id;
    }
}
