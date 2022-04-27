<?php
namespace Admin\Controller;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

class AffiliatesClaimsController extends AbstractActionController
{
    private $em;

    private $config;

    private $firestore;

    private $collection;

    private $route;

    public function __construct($em, $config){
        $this->em = $em;
        $this->config = $config;

        $this->firestore = new \Google\Cloud\Firestore\FirestoreClient([
            'projectId' => 'ospiqyp-oridhean',
            'keyFilePath' => './ospiqyp-firebase-adminsdk-dx723-21cf738448.json'
        ]);

        $this->collection = 'affiliates_claims';
        $this->route = 'admin/affiliates_claims';
    }

    public function indexAction(){
        return new ViewModel([
            'title' => 'Reclamaciones'
        ]);
    }

    public function getAction(){
        return new JsonModel($this->get($this->params()->fromRoute('id', 0)));
    }

    public function get($id = false){
        if($id){
            return $this->em->createQuery('SELECT a FROM Admin\Entity\AffiliateClaim a WHERE a.id = :id')->setParameter('id', $id)->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }else{
            return $this->em->createQuery('
                SELECT c.id, c.detail, c.sector, c.type
                FROM Admin\Entity\AffiliateClaim c 
                ORDER BY c.id ASC')->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }
    }

}