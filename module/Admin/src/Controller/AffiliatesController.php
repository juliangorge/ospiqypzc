<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

class AffiliatesController extends AbstractActionController
{

    private $em;
    private $config;
    private $route;

    public function __construct($em, $config){
        $this->em = $em;
        $this->config = $config;
        $this->route = 'admin/affiliates';
    }

    public function indexAction()
    {
        return new ViewModel([
            'title' => 'Afiliados',
            'route' => $this->route
        ]);
    }

    /*public function importAction(){
        $array = $this->import();
        $array['title'] = 'Importación desde AWS Bucket S3';
        $array['route'] = $this->route;

        return new ViewModel([
            'data' => $array
        ]);
    }*/

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
        $filter['filter_by'] = str_replace('i.full_name', 'i.first_name', $filter['filter_by']);
        $filter['order_by'] = str_replace('i.full_name', 'i.first_name', $filter['order_by']);

        return new JsonModel(
            $plugin->filterForDataTables('Admin\Entity\Affiliates', $filter)
        );
    }

}