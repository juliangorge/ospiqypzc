<?php
namespace Auth\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="users_ranks")
*/
class UserRank
{
	/**
	* @ORM\Id
	* @ORM\Column(name="id", type="integer")
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	protected $id;

    /** @ORM\Column(name="name", type="string", nullable=false) */
    protected $name;

    /** @ORM\Column(name="acl_level", type="string", nullable=false) */
    protected $acl_level;

    public function getId(){
        return $this->id;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getName(){
        return $this->name;
    }

    public function setAclLevel($acl_level){
        $this->acl_level = $acl_level;
    }

    public function getAclLevel(){
        return $this->acl_level;
    }
}