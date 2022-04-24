<?php
namespace Auth\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="users_oauth")
*/
class UserOAuth
{
    /**
    * @ORM\Id
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /** @ORM\Column(name="user_id", type="string", nullable=false) */
    protected $user_id;

    /** @ORM\Column(name="identification", type="string", nullable=false) */
    protected $identification;
    
    public function getArrayCopy(){
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'brand_id' => $this->brand_id,
            'identification' => $this->identification
        ];
    }

    public function exchangeArray(array $array){
        $this->user_id = $array['user_id'];
        $this->brand_id = $array['brand_id'];
        $this->identification = $array['identification'];
    }

    /**
     * Returns user ID.
     * @return integer
     */
    public function getId() 
    {
        return $this->id;
    }

    /**
     * Returns user_id.     
     * @return string
     */
    public function getUserId() 
    {
        return $this->user_id;
    }
    /**
     * Sets user_id.     
     * @param string $user_id
     */
    public function setUserId($user_id) 
    {
        $this->user_id = $user_id;
    }

    /**
     * Returns brand_id.     
     * @return string
     */
    public function getBrandId() 
    {
        return $this->brand_id;
    }
    /**
     * Sets brand_id.     
     * @param string $brand_id
     */
    public function setBrandId($brand_id) 
    {
        $this->brand_id = $brand_id;
    }

    /**
     * Returns identification.
     * @return string
     */
    public function getIdentification() 
    {
        return $this->identification;
    }

    /**
     * Sets identification.
     * @param string $identification
     */
    public function setIdentification($identification) 
    {
        $this->identification = $identification;
    }

}
