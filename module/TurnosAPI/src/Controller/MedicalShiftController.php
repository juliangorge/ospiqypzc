<?php
namespace TurnosAPI\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;

class MedicalShiftController extends AbstractRestfulController
{
    protected $em;
    protected $sm;

    public function __construct($em, $sm)
    {
        $this->em = $em;
        $this->sm = $sm;
    }

    public function indexAction(){
        $id = $this->params()->fromRoute('id', NULL);
        $data = $this->getRequest()->getPost()->toArray();

        if($this->getRequest()->isPost()){            
            return $this->post($data);
        }
        else
        if($this->getRequest()->isPut()){
            return $this->put($data, $id);
        }
        else
        if($this->getRequest()->isPatch()){
            return $this->patch($data, $id);
        }
        else
        if($this->getRequest()->isDelete()){
            return $this->delete($id);
        }

        return $this->get($id);
    }

    public function getByProfessionalDniAction()
    {
        $dni = $this->params()->fromRoute('dni');

        return new JsonModel(
            $this->em->createQuery('
                SELECT 
                m.id, m.shift_datetime, m.dni, m.status, m.date_created
                FROM Admin\Entity\MedicalShift m
                JOIN Admin\Entity\ProfessionalCalendar c WITH c.id = m.professional_calendar_id
                JOIN Admin\Entity\Professional p WITH p.dni = :dni
            ')
            ->setParameter('dni', $dni)
            ->getResult()
        );
    }

    public function getByDniAction()
    {
        $dni = $this->params()->fromRoute('dni');

        return new JsonModel(
            $this->em->createQuery('
                SELECT 
                m.id, m.shift_datetime, m.dni, m.status, m.date_created, p.dni as professional_dni
                FROM Admin\Entity\MedicalShift m
                JOIN Admin\Entity\ProfessionalCalendar c WITH c.id = m.professional_calendar_id
                JOIN Admin\Entity\Professional p WITH p.id = c.professional_id
                WHERE m.dni = :dni
            ')
            ->setParameter('dni', $dni)
            ->getResult()
        );
    }

    public function get($id = NULL)
    {
        $queryBuilder = $this->em->createQueryBuilder()
            ->select('p.id', 'p.user_id', 'p.dni', 'p.specialty_id', 'p.professional_calendar_id', 'p.shift_datetime', 'p.status', 'p.date_created')
            ->from('Admin\Entity\MedicalShift', 'p')
            ->where('p.id = true');

        if ($id !== NULL) {
            $queryBuilder->andWhere('p.id = :id')
                ->setParameter('id', $id);
            $result = $queryBuilder->getQuery()->getOneOrNullResult();
        } else {
            $result = $queryBuilder->getQuery()->getResult();
        }

        return new JsonModel($result);
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

    public function put($data, $id)
    {
        return $this->disallow();
    }

    public function patch($data, $id)
    {
        return $this->disallow();
    }

    public function delete($id)
    {
        return $this->disallow();
    }

    private function disallow(){
        $response = new Response();
        $response->setStatusCode(Response::STATUS_CODE_405);
        return $response;
    }
}