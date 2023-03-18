<?php
namespace TurnosAPI\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;

class MedicalCenterController extends AbstractRestfulController
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

    public function getSpecialtiesAction()
    {
        $id = $this->params()->fromRoute('id');
        $medical_center = $this->em->find('Admin\Entity\MedicalCenter', $id);

        return new JsonModel($medical_center->getSpecialtiesArray());
    }

    public function get($id = NULL)
    {
        $queryBuilder = $this->em->createQueryBuilder()
            ->select('p.id', 'p.name')
            ->from('Admin\Entity\MedicalCenter', 'p')
            ->where('p.is_active = true');

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
        return $this->disallow();
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