<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

class IndexController extends AbstractActionController 
{

    private $em;
    private $config;
    private $route;
    private $serviceManager;

    public function __construct($em, $config){
        $this->em = $em;
        $this->config = $config;
        $this->route = 'admin/dashboard';
    }

    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function indexAction()
    {

    }

    public function logsAction()
    {
        
    }

    public function miCuentaAction()
    {
        return new ViewModel([
            'user' => $this->em->find('Juliangorge\Users\Entity\User', $this->identity()['id'])
        ]);
    }

    public function cambiarContraseñaAction()
    {
        $entity = $this->em->find('Juliangorge\Users\Entity\User', $this->identity()['id']);
        if($entity == NULL) return $this->redirect()->toRoute($this->route);

        $form = new \Juliangorge\Users\Form\PasswordChangeForm($this->em);
        $form->bind($entity);

        $request = $this->getRequest();

        $success = true;
        if($request->isPost()){
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $form->setData($post);

            if($form->isValid()){
                try {
                    $userManager = $this->serviceManager->get($this->config['authModule']['userManager']);
                    $userManager->changePassword($entity, $post, false);
                }catch(\Throwable $e){
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                    $success = false;
                }

            }else{
                $this->flashMessenger()->addErrorMessage($form->getMessages());
                $success = false;
            }
            
            if($success){

                $this->flashMessenger()->addSuccessMessage('Carga exitosa');
                return $this->redirect()->toRoute($this->route, ['action' => 'miCuenta']);
            }
        }

        return new ViewModel([
            'form' => $form,
            'title' => 'Cambiar contraseña',
            'route' => $this->route,
            'item' => $entity,
            'actionForm' => '/' . $this->route . '/cambiar-contraseña'
        ]);
    }

    public function getNotificationsAction()
    {
        return new JsonModel();
    }

}