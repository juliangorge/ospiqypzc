<?php
namespace Admin\Form\Validators;

use Laminas\Validator\AbstractValidator;

class ProfessionalCalendarValidator extends AbstractValidator 
{

    // Available validator options.
    protected $options = [
        'em' => NULL
    ];
    
    // Validation failure message IDs.
    const TIME_NOT_AVAILABLE = 'dayNotAvailable';

    // Validation failure messages.
    protected $messageTemplates = [
    	self::TIME_NOT_AVAILABLE => 'Horario no disponible'
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
        
	// Valido que el horario esté disponible en el calendario de los datos ingresados
    public function isValid($value, $context = NULL)
    {
        $em = $this->options['em'];
        $isValid = false;

        $config = $em->getConfiguration();
        $config->addCustomStringFunction('DATE','DoctrineExtensions\Query\Mysql\Date');
        $config->addCustomStringFunction('JSON_CONTAINS','DoctrineExtensions\Query\Mysql\JsonContains');

        $day = new \DateTime($context['day']);
        $professional_calendar = $em->createQuery('
        	SELECT a
        	FROM Admin\Entity\ProfessionalCalendar a

        	WHERE a.id = :id AND
        	a.medical_center_id = :medical_center_id AND
        	a.professional_id = :professional_id AND
        	DATE(a.starting_at) = :starting_at
        ')
        ->setParameters([
        	'id' => $value,
        	'medical_center_id' => $context['medical_center_id'],
        	'professional_id' => $context['professional_id'],
        	'starting_at' => $day->format('Y-m-d')

        ])
        ->getOneOrNullResult();

        $isValid = $professional_calendar != NULL;

        if($isValid){
        	$isValid = $professional_calendar->checkShift($context['time']) != NULL;
        }

        $this->error(self::TIME_NOT_AVAILABLE);
        return $isValid;
    }
}
