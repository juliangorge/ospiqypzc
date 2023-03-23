<?php
namespace TurnosAPI\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;

class MedicalShiftsController extends AbstractRestfulController
{
    protected $em;
    protected $sm;

    public function __construct($em, $sm)
    {
        $this->em = $em;
        $this->sm = $sm;
    }

    public function indexAction(){
        $medical_shift_id = $this->params()->fromRoute('medical_shift_id', NULL);

        if($this->getRequest()->isPost()){
            #$data = $this->getRequest()->getPost()->toArray();
            $requestBody = $this->getRequest()->getContent();
            $data = json_decode($requestBody, true);
            return $this->post($data);
        }elseif($this->getRequest()->isGet()){
            return $this->get($medical_shift_id);
        }

        return $this->disallow();
    }

    public function getByProfessionalDniAction()
    {
        if(!$this->getRequest()->isGet()){
            return $this->disallow();
        }

        $professional_dni = $this->params()->fromRoute('professional_dni');

        return new JsonModel(
            $this->em->createQuery('
                SELECT 
                m.id, m.shift_datetime, m.dni, m.status, m.date_created
                FROM Admin\Entity\MedicalShift m
                JOIN Admin\Entity\ProfessionalCalendar c WITH c.id = m.professional_calendar_id
                JOIN Admin\Entity\Professional p WITH p.dni = :professional_dni
                ORDER BY m.date_created DESC
            ')
            ->setParameter('professional_dni', $professional_dni)
            ->getResult()
        );
    }

    public function getByDniAction()
    {
        if(!$this->getRequest()->isGet()){
            return $this->disallow();
        }

        $dni = $this->params()->fromRoute('dni');

        return new JsonModel(
            $this->em->createQuery('
                SELECT 
                m.id, m.shift_datetime, m.dni, m.status, m.date_created, p.dni as professional_dni
                FROM Admin\Entity\MedicalShift m
                JOIN Admin\Entity\ProfessionalCalendar c WITH c.id = m.professional_calendar_id
                JOIN Admin\Entity\Professional p WITH p.id = c.professional_id
                WHERE m.dni = :dni
                ORDER BY m.date_created DESC
            ')
            ->setParameter('dni', $dni)
            ->getResult()
        );
    }

    public function get($medical_shift_id = NULL)
    {
        $queryBuilder = $this->em->createQueryBuilder()
            ->select('p.id', 'p.user_id', 'p.dni', 'p.specialty_id', 'p.professional_calendar_id', 'p.shift_datetime', 'p.status', 'p.date_created')
            ->from('Admin\Entity\MedicalShift', 'p');

        if ($medical_shift_id !== NULL) {
            $queryBuilder->andWhere('p.id = :medical_shift_id')
                ->setParameter('medical_shift_id', $medical_shift_id);

            $queryBuilder->orderBy('p.date_created', 'DESC');
            $results = $queryBuilder->getQuery()->getOneOrNullResult();
        } else {

            $queryBuilder->orderBy('p.date_created', 'DESC');
            $results = $queryBuilder->getQuery()->getResult();
        }

        if($results === NULL) return $this->notFound();
        return new JsonModel($results);
    }

    public function post($data)
    {
        $form = new \Admin\Form\MedicalShift($this->em);

        $entity = NULL;
        $formErrors = NULL;
        $success = false;

        $form->setData($data);

        if($form->isValid()){
            $data = $form->getData();

            

            $shift = new \Admin\Entity\MedicalShift($data);
            $this->em->persist($shift);
            $this->em->flush();
            $success = true;
        }else{
            $formErrors = $form->getMessages();
        }

        return new JsonModel([
            'data' => $entity,
            'errors' => $formErrors
        ]);
    }

    public function notFound(){
        $response = new Response();
        $response->setStatusCode(Response::STATUS_CODE_404);
        return $response;
    }

    private function disallow(){
        $response = new Response();
        $response->setStatusCode(Response::STATUS_CODE_405);
        return $response;
    }
}