<?php
namespace Admin\Controller\Plugin; 

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use PHPMailer\PHPMailer\PHPMailer;

class AppPlugin extends AbstractPlugin 
{

    /**
    * Entity Manager.
    * @var Doctrine\ORM\EntityManager 
    */
    private $em;

    private $config;

    private $authManager;

    private $userManager;

    public function __construct($em, $config, $authManager, $userManager){
        $this->em = $em;
        $this->config = $config;
        $this->authManager = $authManager;
        $this->userManager = $userManager;
    }

    public function init($string = false)
    {
        if(!$string) return false;

        /*switch(strtolower($string)){
            case('items'):
                return new \Admin\Controller\ItemsController($this->em, $this->config, $this->authManager, $this->userManager);
        }*/
    }

    public function getWeb(){

        /*$menus = $this->config['menus'];
        $dynamics_menus = $this->init('menus')->getActives();
        
        foreach ($dynamics_menus as $m){
            array_push($menus, $m);
        }*/

        return [
        	'config'		=> $this->config,
            'dynamic'       => $this->em->createQuery('SELECT w FROM Admin\Entity\Web w')->getSingleResult(),
            //'menus'         => $menus,
        ];
    }

    public function getUser(){
        return ($this->authManager->getIdentity() ? $this->authManager->getIdentity()['id'] : null); #$this->authManager->getSessionId()
    }

    public function sendMail($array = []){
        if(sizeof($array) == 0) return false;

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $this->getWeb()['config']['statusEmailHost'];
        $mail->SMTPAuth = true;
        $mail->Username = $this->getWeb()['config']['statusEmail'];
        $mail->Password = $this->getWeb()['config']['statusEmailPassword'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = $this->getWeb()['config']['statusEmailSMTP'];
        $mail->CharSet = 'UTF-8';

        try {
            $mail->setFrom($this->getWeb()['config']['statusEmail'], $this->getWeb()['config']['name']);
            $mail->addAddress($array['email']);
            $mail->isHTML(true);
            $mail->Subject = $array['subject'];
            $mail->Body    = $array['body'];
            $mail->AltBody = html_entity_decode($array['body']);
            $mail->send();
            $mail->ClearAllRecipients();
        } catch (Exception $e) {
            return $mail->ErrorInfo;
        }

        return true;
    }
}