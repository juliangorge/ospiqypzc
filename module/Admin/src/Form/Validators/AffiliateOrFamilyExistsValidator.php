<?php
namespace Admin\Form\Validators;

use Laminas\Validator\AbstractValidator;

class AffiliateOrFamilyExistsValidator extends AbstractValidator 
{

    // Available validator options.
    protected $options = [
        'em' => NULL
    ];
    
    // Validation failure message IDs.
    const DNI_NOT_FOUND = 'DNINotFound';

    // Validation failure messages.
    protected $messageTemplates = [
        self::DNI_NOT_FOUND  => 'DNI no encontrado'
    ];
    
    public function __construct($options = NULL) 
    {
        // Set filter options (if provided).
        if(is_array($options)){            
            if(isset($options['em'])){
                $this->options['em'] = $options['em'];
            }
        }
        
        // Call the parent class constructor
        parent::__construct($options);
    }
        
    public function isValid($value, $context = NULL)
    {
        $em = $this->options['em'];
        $foundValue = false;

        $affiliate = $em->getRepository('Admin\Entity\Affiliates')->findOneBy(['dni' => $value]);
        $foundValue = ($affiliate != NULL);

        if(!$foundValue){
            $family = $em->getRepository('Admin\Entity\AffiliatesFamily')->findOneBy(['dni' => $value]);
            $foundValue = ($family != NULL);
        }

        if(!$foundValue){
            $this->error(self::DNI_NOT_FOUND);
        }

        return $foundValue;
    }
}

