<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Juliangorge\MedicalShifts\Entity\MedicalShiftSupperclassBase;

/**
* This class represents a registered user.
* @ORM\Entity
* @ORM\Table(name="medical_shifts", indexes={
    * @ORM\Index(name="dni", columns={"dni"}),
    * @ORM\Index(name="user_id", columns={"user_id"}),
    * @ORM\Index(name="specialty_id", columns={"specialty_id"})
* })
*/
class MedicalShift extends MedicalShiftSupperclassBase
{
	/** @ORM\Column(name="user_id", type="integer", nullable=true) */
    protected $user_id;

    /** @ORM\Column(name="dni", type="string", nullable=false) */
    protected $dni;

    /** @ORM\Column(name="specialty_id", type="integer", nullable=false) */
    protected $specialty_id;

	public function getArrayCopy(){
    	return [
			'id' => $this->id,
			'user_id' => $this->user_id,
			'dni' => $this->dni,
			'specialty_id' => $this->specialty_id,
			'professional_calendar_id' => $this->professional_calendar_id,
			'shift_datetime' => $this->shift_datetime,
			'status' => $this->status,
			'date_created' => $this->date_created,
		];
    }

	public function __construct(array $array){
		$this->user_id = $array['user_id'];
		$this->dni = $array['dni'];
		$this->specialty_id = $array['specialty_id'];
		$this->status = 0;
		$this->date_created = new \DateTime();
		$this->professional_calendar_id = $array['professional_calendar_id'];
		$this->shift_datetime = \DateTime::createFromFormat('Y-m-d H:i', ($array['day'] . ' ' . $array['time']));
	}

	public function exchangeArray(array $array){
		$this->user_id = $array['user_id'];
		$this->dni = $array['dni'];
		$this->specialty_id = $array['specialty_id'];
		$this->professional_calendar_id = $array['professional_calendar_id'];
		$this->shift_datetime = \DateTime::createFromFormat('Y-m-d H:i', ($array['day'] . ' ' . $array['time']));
	}

	public function getUserId(){ return $this->user_id; }
	public function getDni(){ return $this->dni; }
	public function getSpecialtyId(){ return $this->specialty_id; }
	public function setUserId($v){ $this->user_id = $v; }
	public function setDni($v){ $this->dni = $v; }
	public function setSpecialtyId($v){ $this->specialty_id = $v; }

}