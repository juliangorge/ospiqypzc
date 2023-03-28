<?php
namespace Admin\Controller;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

class AffiliatesFamilyController extends AbstractActionController
{

    protected $em;
    protected $sm;
    protected $config;
    protected $route;

    public function __construct($em, $sm){
        $this->em = $em;
        $this->sm = $sm;
        $this->route = 'admin/affiliates_family';
    }

    public function indexAction()
    {
        return new ViewModel([
            'title' => 'Familiares de Afiliados',
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

    public function getAction(){
        if(!$this->getRequest()->isPost()){
            header('HTTP/1.0 404 Not Found');
            exit;
        }
        
        $data = $this->getRequest()->getPost()->toArray();

        $plugin = $this->plugin(\Admin\Plugin\AppPlugin::class);
        $filterData = $plugin->buildForDataTables($data);

        $filterData['columns'] = str_replace(
            [
                'i.full_name',
                'i.affiliate_full_name'
            ], 
            [
                'CONCAT(i.first_name, \' \', i.last_name) as full_name',
                'CONCAT(a.first_name, \' \', a.last_name) as affiliate_full_name',
            ], 
            $filterData['columns']
        );

        $filterData['filter_by'] = str_replace(['i.full_name','i.affiliate_full_name'], ['i.first_name','a.first_name'], $filterData['filter_by']);
        $filterData['order_by'] = str_replace(['i.full_name','i.affiliate_full_name'], ['i.first_name','a.first_name'], $filterData['order_by']);

        $data = $this->em->createQuery('
            SELECT ' . $filterData['columns'] . '
            FROM Admin\Entity\AffiliatesFamily i
            JOIN Admin\Entity\Affiliates a WITH a.dni = i.affiliate_dni
            ' . ($filterData['filter_by'] != '' ? 'WHERE '. $filterData['filter_by'] : '') . '
            ORDER BY ' . $filterData['order_by'] . '
        ')
        ->setParameters($filterData['parameters'])
        ->setFirstResult($filterData['start'])
        ->setMaxResults($filterData['length'])->getResult();

        return new JsonModel([
            'recordsTotal' => $filterData['length'],
            'recordsFiltered' => $this->em->createQuery('SELECT COUNT(i.id) FROM Admin\Entity\AffiliatesFamily i')->getSingleScalarResult(),
            'data' => $data
        ]);
    }

}