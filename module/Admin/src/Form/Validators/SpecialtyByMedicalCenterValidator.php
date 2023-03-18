<?php
namespace Admin\Form\Validators;

use Laminas\Validator\AbstractValidator;

class SpecialtyByMedicalCenterValidator extends AbstractValidator 
{

    // Available validator options.
    protected $options = [
        'em' => NULL
    ];
    
    // Validation failure message IDs.
    const INVALID_MEDICAL_CENTER = 'invalidMedicalCenter';
    const INVALID_SPECIALTY = 'invalidSpecialty';

    // Validation failure messages.
    protected $messageTemplates = [
    	self::INVALID_MEDICAL_CENTER  => 'Centro médico inválido',
        self::INVALID_SPECIALTY  => 'Especialidad inválida',
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
        
	// Valido que el Centro Médico atienda la Especialidad ingresada
    public function isValid($value, $context = NULL)
    {
        $em = $this->options['em'];
        $isValid = false;

        $medical_center = $em->find('Admin\Entity\MedicalCenter', $value);
        if($medical_center == NULL){
        	$isValid = false;
        	$this->error(self::INVALID_MEDICAL_CENTER);
        }
        else{
            $isValid = $medical_center->hasSpecialty($context['specialty_id']);
            if(!$isValid){
                $this->error(self::INVALID_SPECIALTY);
            }
        }

        return $isValid;
    }
}

