<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

use function GuzzleHttp\debug_resource;

class AffiliatesController extends AbstractActionController
{

    protected $em;
    protected $sm;
    protected $config;
    protected $route;

    public function __construct($em, $sm){
        $this->em = $em;
        $this->sm = $sm;
        $this->config = $sm->get('config');

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

    public function getAction()
    {
        if(!$this->getRequest()->isPost()) return $this->appPlugin()->return403();
        $data = $this->getRequest()->getPost()->toArray();
        
        $filterData = $this->appPlugin()->buildForDataTables($data);
        $filterData['columns'] = str_replace('i.full_name', 'CONCAT(i.first_name, \' \', i.last_name) as full_name', $filterData['columns']);
        $filterData['filter_by'] = str_replace('i.full_name', 'i.first_name', $filterData['filter_by']);
        $filterData['order_by'] = str_replace('i.full_name', 'i.first_name', $filterData['order_by']);

        $data = $this->em->createQuery('
            SELECT ' . $filterData['columns'] . '
            FROM Admin\Entity\Affiliates i 
            WHERE i.is_active = 1 
            ' . ($filterData['filter_by'] != '' ? ' AND ('. $filterData['filter_by'] . ')' : '') . '
            ORDER BY ' . $filterData['order_by'] . '
        ')
        ->setParameters($filterData['parameters'])
        ->setFirstResult($filterData['start'])
        ->setMaxResults($filterData['length'])->getResult();

        $recordsFiltered = $this->em->createQuery('
            SELECT COUNT(i.id) 
            FROM Admin\Entity\Affiliates i
            WHERE i.is_active = 1
        ')->getSingleScalarResult();

        return new JsonModel([
            'recordsTotal' => $filterData['length'],
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

}