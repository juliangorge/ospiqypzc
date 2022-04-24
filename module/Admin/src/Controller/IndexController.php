<?php
namespace Admin\Controller;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Users\Entity\User;

class IndexController extends AbstractActionController 
{
    private $em;

    private $config;

    public function __construct($em, $config){
        $this->em = $em;
        $this->config = $config;
    }

    public function indexAction(){
    }

    public function disableWebsiteAction(){
        $w = $this->em->find('Admin\Entity\Web', 1);
        $w->setDisableWebsite(!$w->getDisableWebsite());
        $this->em->flush();
        return $this->redirect()->toRoute('admin/dashboard');
    }

    public function cURLHandler($method, $url, $post_fields, $content_type){
        try {
            $ch = curl_init();

            // Check if initialization had gone wrong*    
            if ($ch === false) throw new Exception('Failed to initialize');

            if($content_type == true){
                $http_header = ['Content-Type: application/json'];
            }elseif($content_type == false){
                $http_header = ['Content-Type: application/x-www-form-urlencoded'];
            }else{
                $http_header = $content_type;
            }

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $http_header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
            curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);

            if(strtoupper($method) == 'POST'){
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, (!$content_type ? http_build_query($post_fields) : json_encode($post_fields)));
            }
            if(strpos($_SERVER['SERVER_NAME'], 'test') !== false){
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            }

            $content = curl_exec($ch);

            // Check the return value of curl_exec(), too
            if($content === false) 
                throw new \Exception(curl_error($ch), curl_errno($ch));

            curl_close($ch);

        } catch(\Exception $e) {
            trigger_error(sprintf('Curl failed with error #%d: %s', $e->getCode(), $e->getMessage()), E_USER_ERROR);
        }

        return $content;
    }

}