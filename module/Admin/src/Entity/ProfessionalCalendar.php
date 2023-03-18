<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Juliangorge\MedicalShifts\Entity\ProfessionalCalendarSupperclassBase;

/**
* This class represents a registered user.
* @ORM\Entity
* @ORM\Table(name="professional_calendar")
*/
class ProfessionalCalendar extends ProfessionalCalendarSupperclassBase
{

	public function checkShift(string $hour) : bool {
		$array = json_decode($this->shifts_offer);
		return in_array($hour, $array);
	}

	public function removeShiftOffer(string $hour) {
		$array = json_decode($this->shifts_offer);

		if(($key = array_search($hour, $array)) !== false){
    		unset($array[$key]);
		}

		$this->shifts_offer = json_encode(array_values($array));
	}

}