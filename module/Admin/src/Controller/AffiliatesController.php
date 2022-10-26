<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Storage\StorageClient;

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
        return new ViewModel([
            'title' => 'Afiliados',
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
        $start = microtime(true);
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

    public function getAction(){
        if(!$this->getRequest()->isPost()){
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        $data = $this->getRequest()->getPost()->toArray();

        $plugin = $this->plugin(\Admin\Plugin\AppPlugin::class);
        $filter = $plugin->buildForDataTables($data);

        $filter['columns'] = str_replace('i.full_name', 'CONCAT(i.first_name, \' \', i.last_name) as full_name', $filter['columns']);
        $filter['order_by'] = str_replace('i.full_name', 'i.first_name', $filter['order_by']);

        return new JsonModel(
            $plugin->filterForDataTables('Admin\Entity\Affiliates', $filter)
        );
    }

}