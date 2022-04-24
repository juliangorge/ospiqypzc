<?php
namespace Auth\Service;

use Laminas\Authentication\Adapter\AdapterInterface;
use Laminas\Authentication\Result;
use Laminas\Crypt\Password\Bcrypt;
use Auth\Entity\User;
use Auth\Entity\UserRank;

class AuthAdapter implements AdapterInterface
{
    private $email;

    private $password;

    private $oauth;
    
    private $em;
        
    public function __construct($em){
        $this->em = $em;
    }
    
    public function setEmail($email){
        $this->email = $email;
    }
    
    public function setPassword($password){
        $this->password = (string) $password;
    }

    public function setOAuth($oauth){
        $this->oauth = $oauth;
    }
    
    public function authenticate(){
        
        $user = $this->em->getRepository(User::class)->findOneByEmail($this->email);
        
        if ($user == NULL)
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, NULL, ['Credenciales inválidas.']);
        
        if(!$user->getStatus())
            return new Result(Result::FAILURE, NULL, ['Usuario bloqueado.']);

        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();

        if ($this->oauth || $bcrypt->verify($this->password, $passwordHash)) {
            $rank = $this->em->find(UserRank::class, $user->getRankId());

            return new Result(
                Result::SUCCESS, 
                [
                    'id'  => $user->getId(),
                    'email' => $this->email,
                    'firstname'  => $user->getFirstname(),
                    'lastname'  => $user->getLastname(),
                    'rank_id'  => $user->getRankId(),
                    'rank_name' => $rank->getName(),
                    'rank_level' => $rank->getAclLevel()
                ],
                ['Autenticación exitosa.']);
        }             
        
        return new Result(Result::FAILURE_CREDENTIAL_INVALID, NULL, ['Credenciales inválidas.']);
    }
}