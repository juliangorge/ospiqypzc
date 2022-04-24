<?php
namespace Auth\Validator;

use Laminas\Validator\AbstractValidator;

class UserExistsValidator extends AbstractValidator 
{
    protected $options = [
        'em' => null
    ];
    
    const NOT_SCALAR  = 'notScalar';
    const USER_EXISTS = 'userExists';

    protected $messageTemplates = array(
        self::NOT_SCALAR  => 'El email debe tener un valor válido',
        self::USER_EXISTS  => 'Ya hay un usuario utilizando este email'
    );
    
    public function __construct($options = null) 
    {
        if(is_array($options)) {            

            if(isset($options['em'])){
                $this->options['em'] = $options['em'];
            }
        }
        
        parent::__construct($options);
    }

    public function isValid($value) 
    {
        if(!is_scalar($value)) {
            $this->error(self::NOT_SCALAR);
            return false; 
        }
        
        $em = $this->options['em'];
        $user = $em->getRepository('Auth\Entity\User')->findOneByEmail($value);
        
        $isValid = ($user == NULL);

        if(!$isValid) $this->error(self::USER_EXISTS);

        return $isValid;
    }
}

