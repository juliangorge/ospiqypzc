<?php
namespace TurnosAPI\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Http\Response;

class ProfessionalsController extends AbstractRestfulController
{
    protected $em;
    protected $sm;

    public function __construct($em, $sm)
    {
        $this->em = $em;
        $this->sm = $sm;
    }

    public function indexAction(){
        $professional_id = $this->params()->fromRoute('professional_id', NULL);

        if(!$this->getRequest()->isGet()){
            return $this->disallow();
        }

        return $this->get($professional_id);
    }

    public function get($professional_id = NULL)
    {
        $parameters = ['professional_id' => $professional_id];
        if($professional_id === NULL) unset($parameters['professional_id']);

        $results = $this->em->createQuery('
            SELECT p.id, p.first_name, p.last_name, p.dni, p.registration, p.college, p.cuit
            FROM Admin\Entity\Professional p
            WHERE p.is_active = true
            ' . ($professional_id != NULL ? 'AND p.id = :professional_id' : '') . '
        ')
        ->setParameters($parameters);

        if($professional_id != NULL){
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
