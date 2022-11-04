<?php
namespace Admin\Plugin; 

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

class AppPlugin extends AbstractPlugin 
{

    protected $em;
    protected $config;
    protected $authManager;
    protected $userManager;

    public function __construct($em, $config, $authManager, $userManager){
        $this->em = $em;
        $this->config = $config;
        $this->authManager = $authManager;
        $this->userManager = $userManager;
    }

    public function getWeb(){
        return [
            'config'   => $this->config,
            #'dynamic'  => $this->em->createQuery('SELECT w FROM Admin\Entity\Web w')->getSingleResult(),
        ];
    }

    public function ping(){
        return 'pong';
    }

    public function buildForDataTables(array $postData){
        $column_array = [];
        $columns = '';
        $filter_by = '';

        foreach($postData['columns'] as $key => $value){
            if($columns != '') $columns .= ', ';

            if(is_array($value['data'])){
                $subcolumns = '';
                foreach($value['data'] as $data){
                    if($subcolumns != '') $subcolumns .= ', ';

                    $subcolumns .= 'i.' . $data;
                    $columns_array[$key] = $data;
                }

                $columns .= $subcolumns;
            }else{
                $columns .= 'i.' . $value['data'];
                $columns_array[$key] = $value['data'];
            }

            if($value['searchable'] == 'true' && $postData['search']['value'] != ''){
                if($filter_by != '') $filter_by .= ' OR ';

                if(is_array($value['data'])){
                    $subfilter_by = '';
                    foreach($value['data'] as $data){
                        if($subfilter_by != '') $subfilter_by .= ' OR ';

                        $subfilter_by .= 'i.' . $data . ' LIKE :search_value ';
                    }
                }else{
                    $filter_by .= 'i.' . $value['data'] . ' LIKE :search_value ';
                }
            }
        }

        $order_by = '';
        if(isset($postData['order'])){
            foreach($postData['order'] as $order){
                if($order_by != '') $order_by = ', ';
                $order_by .= 'i.' . $columns_array[$order['column']] . ' ' . $order['dir'];
            }
        }

        $parameters = [];
        if($postData['search']['value'] != ''){
            $parameters['search_value'] = '%' . $postData['search']['value'] . '%';
        }

        return [
            'parameters' => $parameters,
            'columns' => $columns,
            'order_by' => $order_by,
            'filter_by' => $filter_by,
            'start' => $postData['start'],
            'length' => $postData['length']
        ];
    }

    public function filterForDataTables(string $entity, array $filterData){
        $data = $this->em->createQuery('
            SELECT ' . $filterData['columns'] . '
            FROM ' . $entity . ' i
            ' . ($filterData['filter_by'] != '' ? 'WHERE '. $filterData['filter_by'] : '') . '
            ' . ($filterData['order_by'] != '' ? 'ORDER BY ' . $filterData['order_by'] : '') . '
        ')
        ->setParameters($filterData['parameters'])
        ->setFirstResult($filterData['start'])
        ->setMaxResults($filterData['length'])->getResult();

        return [
            'recordsTotal' => $filterData['length'],
            'recordsFiltered' => $this->em->createQuery('SELECT COUNT(i.id) FROM ' . $entity . ' i')->getSingleScalarResult(),
            'data' => $data
        ];
    }
}