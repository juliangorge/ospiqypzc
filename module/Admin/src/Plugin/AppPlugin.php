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

    public function getUser(){
        return ($this->authManager->getIdentity() ? $this->authManager->getIdentity()['id'] : null);
    }
}