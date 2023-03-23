<?php
namespace TurnosAPI\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;

class IndexController extends AbstractRestfulController
{
    protected $em;
    protected $sm;

    public function __construct($em, $sm)
    {
        $this->em = $em;
        $this->sm = $sm;
    }

    public function indexAction(){
        return new JsonModel();
    }
}