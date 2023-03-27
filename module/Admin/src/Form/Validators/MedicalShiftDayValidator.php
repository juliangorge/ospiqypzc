<?php
namespace Admin\Form\Validators;

use Laminas\Validator\AbstractValidator;

class MedicalShiftDayValidator extends AbstractValidator 
{

    // Available validator options.
    protected $options = [
        'em' => NULL
    ];
    
    // Validation failure message IDs.
    const INVALID_SPECIALTY = 'invalidSpecialty';
    const PROFESSIONAL_NOT_FOUND = 'professionalNotFound';
    const DAY_NOT_AVAILABLE = 'dayNotAvailable';

    // Validation failure messages.
    protected $messageTemplates = [
    	self::INVALID_SPECIALTY => 'Especialidad inválida',
    	self::PROFESSIONAL_NOT_FOUND => 'Profesional no encontrado',
        self::DAY_NOT_AVAILABLE => 'Día no disponible'
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

        $professional = $em->find('Admin\Entity\Professional', $context['professional_id']);

        $day = new \DateTime($value);
        $calendar = $this->getProfessionalCalendar($professional, $day->format('Y-m-d'));
        $isValid = $calendar != NULL;

        if(!$isValid) $this->error(self::DAY_NOT_AVAILABLE);
        return $isValid;
    }

    private function getProfessionalCalendar($professional, $date)
    {
        $em = $this->options['em'];
        $config = $em->getConfiguration();
        $config->addCustomStringFunction('DATE_FORMAT','DoctrineExtensions\Query\Mysql\DateFormat');

        return $em->createQuery('
            SELECT i FROM Admin\Entity\ProfessionalCalendar i
            WHERE i.professional_id = :professional_id AND 
            DATE_FORMAT(i.starting_at, \'%Y-%m-%d\') = :date_string
        ')
        ->setParameters([
            'professional_id' => $professional->getId(),
            'date_string' => $date
        ])
        ->getOneOrNullResult();
    }
}

