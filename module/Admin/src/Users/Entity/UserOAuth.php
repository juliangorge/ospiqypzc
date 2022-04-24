<?php

namespace Users\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserOAuth
 *
 * @ORM\Table(name="users_oauth")
 * @ORM\Entity
 */
class UserOAuth
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
     * @ORM\Column(name="user_id", type="string", nullable=false, unique=false)
     */
    private $user_id;

    /**
     * @var string
     *
     * @ORM\Column(name="identification", type="string", nullable=false, unique=false)
     */
    private $identification;


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
     * Set userId.
     *
     * @param string $userId
     *
     * @return UserOAuth
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get userId.
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set identification.
     *
     * @param string $identification
     *
     * @return UserOAuth
     */
    public function setIdentification($identification)
    {
        $this->identification = $identification;

        return $this;
    }

    /**
     * Get identification.
     *
     * @return string
     */
    public function getIdentification()
    {
        return $this->identification;
    }
}
