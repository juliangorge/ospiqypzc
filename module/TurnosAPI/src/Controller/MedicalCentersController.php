<?php
namespace TurnosAPI\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Http\Response;

class MedicalCentersController extends AbstractRestfulController
{
    protected $em;
    protected $sm;

    public function __construct($em, $sm)
    {
        $this->em = $em;
        $this->sm = $sm;
    }

    public function indexAction()
    {
        $medical_center_id = $this->params()->fromRoute('medical_center_id', NULL);

        if(!$this->getRequest()->isGet()){
            return $this->disallow();
        }

        return $this->get($medical_center_id);
    }

    public function get($medical_center_id = NULL)
    {
        $queryBuilder = $this->em->createQueryBuilder()
            ->select('p.id', 'p.name', 'p.document_id')
            ->from('Admin\Entity\MedicalCenter', 'p');

        if ($medical_center_id !== NULL) {
            $queryBuilder->andWhere('p.id = :id')->setParameter('id', $medical_center_id);
            $results = $queryBuilder->getQuery()->getOneOrNullResult();
        } else {
            $results = $queryBuilder->getQuery()->getResult();
        }

        if($result === NULL) return $this->notFound();
        return new JsonModel($results);
    }

    public function getProfessionalsBySpecialtyAction()
    {
        if(!$this->getRequest()->isGet()){
            return $this->disallow();
        }

        $specialty_id = $this->params()->fromRoute('specialty_id');
        $parameters = ['specialty_id' => $specialty_id];

        $specialty_id = $this->em->createQuery('
            SELECT s.id
            FROM Admin\Entity\MedicalCenter m
            JOIN m.specialties s
            WHERE s.id = :specialty_id
        ')
        ->setParameters($parameters)
        ->getSingleScalarResult();

        if($specialty_id == NULL) return $this->notFound();

        $results = $this->em->createQuery('
            SELECT p.id, p.first_name, p.last_name, p.dni, p.registration, p.college, p.cuit
            FROM Admin\Entity\Professional p
            JOIN p.specialties s
            WHERE p.is_active = true AND s.id IN (:specialty_id)
        ')
        ->setParameter('specialty_id', $specialty_id)
        ->getResult();

        return new JsonModel($results);
    }

    public function getDaysByProfessionalAction()
    {
        if(!$this->getRequest()->isGet()){
            return $this->disallow();
        }

        $professional_id = $this->params()->fromRoute('professional_id');
        $medical_center_id = $this->params()->fromRoute('medical_center_id');

        $config = $this->em->getConfiguration();
        $config->addCustomStringFunction('DATE_FORMAT','DoctrineExtensions\Query\Mysql\DateFormat');

        $results = $this->em->createQuery('
            SELECT
            c.id as professional_calendar_id, DATE_FORMAT(c.starting_at, \'%Y-%m-%d\') as value
            FROM Admin\Entity\ProfessionalCalendar c
            WHERE 
            c.professional_id = :professional_id AND c.medical_center_id = :medical_center_id AND
            c.ending_at > :now
        ')
        ->setParameters([
            'professional_id' => $professional_id,
            'medical_center_id' => $medical_center_id,
            'now' => new \DateTime()
        ])
        ->getResult();

        return new JsonModel($results);
    }

    public function getTimesByProfessionalAction()
    {
        if(!$this->getRequest()->isGet()){
            return $this->disallow();
        }

        $professional_id = $this->params()->fromRoute('professional_id');
        $medical_center_id = $this->params()->fromRoute('medical_center_id');
        $day = $this->params()->fromRoute('day');

        $config = $this->em->getConfiguration();
        $config->addCustomStringFunction('DATE','DoctrineExtensions\Query\Mysql\Date');

        $results = $this->em->createQuery('
            SELECT
            c.id as professional_calendar_id, c.shifts_offer
            FROM Admin\Entity\ProfessionalCalendar c
            WHERE c.professional_id = :professional_id AND c.medical_center_id = :medical_center_id AND DATE(c.starting_at) = :starting_at
        ')
        ->setParameters([
            'professional_id' => $professional_id,
            'medical_center_id' => $medical_center_id,
            'starting_at' => (new \DateTime($day))->format('Y-m-d')
        ])
        ->getOneOrNullResult();

        if($results == NULL) return $this->notFound();

        return new JsonModel([
            'professional_calendar_id' => $results['professional_calendar_id'],
            'shifts_offer' => json_decode($results['shifts_offer'], true)
        ]);
    }

    private function notFound(){
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