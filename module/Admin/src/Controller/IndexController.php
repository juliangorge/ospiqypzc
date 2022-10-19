<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

class IndexController extends AbstractActionController 
{

    private $em;

    private $config;

    public function __construct($em, $config){
        $this->em = $em;
        $this->config = $config;
    }

    public function indexAction()
    {

    }

    public function logsAction()
    {
        
    }

    public function getNotificationsAction()
    {
        return new JsonModel();
    }

}