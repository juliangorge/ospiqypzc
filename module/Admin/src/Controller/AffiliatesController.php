<?php
namespace Admin\Controller;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Storage\StorageClient;

use Doctrine\Common\Collections\ArrayCollection;
use DoctrineModule\Paginator\Adapter\Collection as CollectionAdapter;
use Laminas\Paginator\Paginator;

class AffiliatesController extends AbstractActionController
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

        $this->collection = 'affiliates_data';
        $this->route = 'admin/affiliates';
    }

    public function indexAction()
    {
        $doctrineCollection = new ArrayCollection($this->fetchAll());
        $adapter = new CollectionAdapter($doctrineCollection);
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p', 1))->setItemCountPerPage(30);

        return new ViewModel([
            'title' => 'Afiliados',
            'results' => $paginator,
            'route' => $this->route
        ]);
    }

    public function importAction(){
        $array = $this->import();
        $array['title'] = 'Importación desde AWS Bucket S3';
        $array['route'] = $this->route;

        return new ViewModel([
            'data' => $array
        ]);
    }

    public function importFromCronAction(){
        if($_SERVER['HTTP_USER_AGENT'] != $this->config['cronKey']){
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        $this->import();
        return new JsonModel(['success' => true]);
    }

    private function import(){
        $bucket = new \Admin\Service\AffiliatesBucket($this->em, $this->config);

        try {
            $results = $bucket->import();
        }
        catch(\Throwable $e){
            return [
                'quantity' => -1, 
                'errors' => $e->getMessage(),
                'time' => microtime(true) - $start
            ];
        }

        return $results;
    }

    private function fetchAll($as_array = false){
        return $this->em->createQuery('SELECT i FROM Admin\Entity\Affiliates i ORDER BY i.id DESC')->getResult($as_array ? \Doctrine\ORM\Query::HYDRATE_ARRAY : NULL);
    }

}