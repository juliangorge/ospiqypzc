<?php
namespace Admin\Form\Validators;

use Laminas\Validator\AbstractValidator;

class SpecialtyByProfessionalValidator extends AbstractValidator 
{

    // Available validator options.
    protected $options = [
        'em' => NULL
    ];
    
    // Validation failure message IDs.
    const INVALID_SPECIALTY = 'invalidSpecialty';
    const PROFESSIONAL_NOT_FOUND = 'professionalNotFound';

    // Validation failure messages.
    protected $messageTemplates = [
    	self::INVALID_SPECIALTY  => 'Especialidad inválida',
    	self::PROFESSIONAL_NOT_FOUND  => 'Profesional no encontrado',
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
        
	// Valido que el Profesional atienda en la Especialidad ingresada
    public function isValid($value, $context = NULL)
    {
        $em = $this->options['em'];
        $isValid = false;

        $professional = $em->find('Admin\Entity\Professional', $value);
        if($professional == NULL){
        	$isValid = false;
        	$this->error(self::PROFESSIONAL_NOT_FOUND);
        }
        else{
            $isValid = $professional->hasSpecialty($context['specialty_id']);
            if(!$isValid){
                $this->error(self::INVALID_SPECIALTY);
            }
        }

        return $isValid;
    }
}

