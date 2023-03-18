<?php
namespace Admin\Form\Validators;

use Laminas\Validator\AbstractValidator;

class MedicalCenterExistsValidator extends AbstractValidator 
{

    // Available validator options.
    protected $options = [
        'em' => NULL
    ];
    
    // Validation failure message IDs.
    const MEDICAL_CENTER_NOT_FOUND = 'medicalCenterNotFound';

    // Validation failure messages.
    protected $messageTemplates = [
        self::MEDICAL_CENTER_NOT_FOUND  => 'Centro Médico no encontrado'
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
        
        $medical_center = $em->getRepository('Admin\Entity\MedicalCenter')->findOneBy(['id' => $value]);
        $foundValue = ($medical_center != NULL);

        if(!$foundValue){
            $this->error(self::MEDICAL_CENTER_NOT_FOUND);
        }

        return $foundValue;
    }
}

