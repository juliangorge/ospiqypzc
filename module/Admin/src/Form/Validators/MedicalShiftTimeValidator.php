<?php
namespace Admin\Form\Validators;

use Laminas\Validator\AbstractValidator;

class MedicalShiftTimeValidator extends AbstractValidator 
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

        $day = new \DateTime($context['day']);

        $professional = $em->find('Admin\Entity\Professional', $context['professional_id']);
        $calendar = $this->getProfessionalCalendar($professional, $day->format('Y-m-d'));
        $isValid = $calendar->checkShift($context['time']);

        if(!$isValid){
        	$this->error(self::TIME_NOT_AVAILABLE);
        }

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

