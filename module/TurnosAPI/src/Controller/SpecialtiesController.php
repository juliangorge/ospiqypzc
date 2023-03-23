<?php
namespace TurnosAPI\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Http\Response;

class SpecialtiesController extends AbstractRestfulController
{
    protected $em;
    protected $sm;

    public function __construct($em, $sm)
    {
        $this->em = $em;
        $this->sm = $sm;
    }

    public function indexAction(){
        $medical_center_id = $this->params()->fromRoute('medical_center_id', NULL);
        $specialty_id = $this->params()->fromRoute('specialty_id', NULL);

        if(!$this->getRequest()->isGet()){
            return $this->disallow();
        }

        return $this->get($medical_center_id, $specialty_id);
    }

    public function get($medical_center_id, $specialty_id = NULL)
    {
        $parameters = [
            'medical_center_id' => $medical_center_id,
            'specialty_id' => $specialty_id
        ];

        if($specialty_id === NULL) unset($parameters['specialty_id']);

        $results = $this->em->createQuery('
            SELECT p.id, p.name, p.document_id
            FROM Admin\Entity\MedicalCenter m
            JOIN m.specialties p
            WHERE m.id = :medical_center_id
            ' . ($specialty_id != NULL ? 'AND p.id = :specialty_id' : '') . '
        ')
        ->setParameters($parameters);

        if($specialty_id != NULL){
            $results = $results->getOneOrNullResult();
        }
        else{
            $results = $results->getResult();
        }

        if($results === NULL) return $this->notFound();
        return new JsonModel($results);
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
