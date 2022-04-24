<?php
namespace Auth\Entity;

use Doctrine\ORM\Mapping as ORM;
use Laminas\Crypt\Password\Bcrypt;

/**
* @ORM\Entity
* @ORM\Table(name="users")
*/
class User
{
    // Modificar
    const GUEST_RANK = 2;

    const STATUS_RETIRED = 0;
    const STATUS_ACTIVE = 1;

    /**
    * @ORM\Id
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /** @ORM\Column(name="email", type="string", nullable=false) */
    protected $email;

    /** @ORM\Column(name="firstname", type="string", nullable=false) */
    protected $firstname;

    /** @ORM\Column(name="lastname", type="string", nullable=false) */
    protected $lastname;

    /** @ORM\Column(name="password", type="string", nullable=false) */
    protected $password;

    /** @ORM\Column(name="status", type="integer", nullable=false) */
    protected $status;

    /** @ORM\Column(name="rank_id", type="integer", nullable=false) */
    protected $rank_id;

    /** @ORM\Column(name="date_created", type="datetime", nullable=false) */
    protected $date_created;

    /** @ORM\Column(name="pwd_reset_token", type="string", nullable=true) */
    protected $password_reset_token;
    
    /** @ORM\Column(name="pwd_reset_token_creation_date", type="datetime", nullable=true) */
    protected $pwd_reset_token_creation_date;

    public function getArrayCopy(){
        return [
            'id' => $this->id,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'password' => $this->password,
            'status' => $this->status,
            'rank_id' => $this->rank_id,
            'date_created' => $this->date_created,
            'password_reset_token' => $this->password_reset_token,
            'pwd_reset_token_creation_date' => $this->pwd_reset_token_creation_date,
        ];
    }

    public function initialize(array $array){
        $this->email = $array['email'];
        $this->firstname = $array['firstname'];
        $this->lastname = $array['lastname'];
        $this->password = $this->encryptPassword($array['password']);
        $this->status = self::STATUS_ACTIVE;
        $this->rank_id = self::GUEST_RANK;
        $this->date_created = new \DateTime();
    }

    public function exchangeArray(array $array){
        $this->email = $array['email'];
        $this->firstname = $array['firstname'];
        $this->lastname = $array['lastname'];
        $this->status = $array['status'];
        $this->rank_id = $array['rank_id'];
    }

    public function getId(){ return $this->id; }
    public function getEmail(){ return $this->email; }
    public function getFirstname(){ return $this->firstname; }
    public function getLastname(){ return $this->lastname; }
    public function getStatus(){ return $this->status; }
    public function getRankId(){ return $this->rank_id; }
    public function getPassword(){ return $this->password; }
    public function getDateCreated(){ return $this->date_created; }
    public function getPasswordResetToken(){ return $this->password_reset_token; }
    public function getPasswordResetTokenCreationDate(){ return $this->pwd_reset_token_creation_date; }

    public function setEmail($v){ $this->email = $v; }
    public function setFirstname($v){ $this->firstname = $v; }
    public function setLastname($v){ $this->lastname = $v; }
    public function setStatus($v){ $this->status = $v; }
    public function setRankId($v){ $this->rank_id = $v; }
    public function setPassword($v){ $this->password = $this->encryptPassword($v); }
    public function setDateCreated($v){ $this->date_created = $v; }
    public function setPasswordResetToken($v){ $this->password_reset_token = $v; }
    public function setPasswordResetTokenCreationDate($v){ $this->pwd_reset_token_creation_date = $v; }

    private function encryptPassword(string $string){
        $bcrypt = new Bcrypt();
        return $bcrypt->create($string);
    }

}
