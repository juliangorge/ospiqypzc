<?php
namespace Admin\Controller;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

use Google\Cloud\Firestore\FirestoreClient;
use Doctrine\Common\Collections\ArrayCollection;
use DoctrineModule\Paginator\Adapter\Collection as CollectionAdapter;
use Laminas\Paginator\Paginator;

class AffiliatesFamilyController extends AbstractActionController
{

    private $em;
    private $config;
    private $firestore;
    private $colection;
    private $route;

    public function __construct($em, $config){
        $this->em = $em;
        $this->config = $config;

        $this->firestore = new FirestoreClient([
            'projectId' => $this->config['firestore_projectId'],
            'keyFilePath' => $this->config['firestore_keyFilePath']
        ]);

        $this->collection = 'affiliates_family';
        $this->route = 'admin/affiliates_family';
    }

    public function indexAction()
    {
        $doctrineCollection = new ArrayCollection($this->fetchAll());
        $adapter = new CollectionAdapter($doctrineCollection);
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p', 1))->setItemCountPerPage(30);

        return new ViewModel([
            'title' => 'Familiares de Afiliados',
            'results' => $paginator,
            'route' => $this->route
        ]);
    }

    private function fetchAll(){
        return $this->em->createQuery('
            SELECT 
            CONCAT(i.first_name, \' \', i.last_name) as full_name, i.dni, i.email, i.affiliate_dni, i.phone_number,
            CONCAT(a.first_name, \' \', a.last_name) as affiliate_full_name
            FROM Admin\Entity\AffiliatesFamily i 
            INNER JOIN Admin\Entity\Affiliates a WITH a.dni = i.affiliate_dni
            ORDER BY a.last_name DESC')->getResult();
    }

}