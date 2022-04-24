<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="web")
*/
class Web
{
    /**
    * @ORM\Id
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /** @ORM\Column(name="date", type="date", nullable=false) */
    protected $date;

    /** @ORM\Column(name="logo", type="integer", nullable=false) */
    protected $logo;

    /** @ORM\Column(name="refresh_token", type="string", nullable=true) */
    protected $refresh_token;

    /** @ORM\Column(name="refresh_token_expires", type="datetime", nullable=true) */
    protected $refresh_token_expires;

    /** @ORM\Column(name="disable_website", type="integer", nullable=true) */
    protected $disable_website;

    public function getDate(){ return $this->date; }
    public function getLogo(){ return $this->logo; }
    public function getRefreshToken(){ return $this->refresh_token; }
    public function getRefreshTokenExpires(){ return $this->refresh_token_expires; }
    public function getDisableWebsite(){ return $this->disable_website;}

    public function setLogo($v){ $this->logo = $v; }
    public function setRefreshToken($v){ $this->refresh_token = $v; }
    public function setRefreshTokenExpires($v){ $this->refresh_token_expires = $v; }
    public function setDisableWebsite($v){ $this->disable_website = $v; }
}