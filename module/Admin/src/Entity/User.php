<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Juliangorge\Users\Entity\UserSuperclassBase;
use Laminas\Crypt\Password\Bcrypt;

/**
* @ORM\Entity
* @ORM\Table(name="users")
*/
class User extends UserSuperclassBase
{

	/** @ORM\Column(name="first_name", type="string", nullable=false) */
    protected $first_name;

    /** @ORM\Column(name="last_name", type="string", nullable=false) */
    protected $last_name;

	public function getArrayCopy(){
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'password' => $this->password,
            'status' => $this->status,
            'role_id' => $this->role_id,
            'date_created' => $this->date_created,
            'password_reset_token' => $this->password_reset_token,
            'password_reset_token_date' => $this->password_reset_token_date,
            'last_modified_date' => $this->last_modified_date
        ];
    }

    public function __construct(array $array){
    	$this->first_name = $array['first_name'];
    	$this->last_name = $array['last_name'];
        $this->email = $array['email'];

        $bcrypt = new Bcrypt();
        $this->password = $bcrypt->create($array['password']);

        $this->status = self::ACTIVE;
        $this->role_id = $array['role_id'];
        $this->date_created = new \DateTime();
        $this->last_modified_date = new \DateTime();
    }

    public function exchangeArray(array $array){
        $this->first_name = $array['first_name'];
        $this->last_name = $array['last_name'];
		$this->status = $array['status'];
        $this->role_id = $array['role_id'];
        $this->last_modified_date = new \DateTime();
    }

    public function getDisplayName(){ return $this->first_name . ' ' . $this->last_name; }

    public function getFirstName(){ return $this->first_name; }
	public function setFirstName($v){ $this->first_name = $v; }

	public function getLastName(){ return $this->last_name; }
	public function setLastName($v){ $this->last_name = $v; }

}