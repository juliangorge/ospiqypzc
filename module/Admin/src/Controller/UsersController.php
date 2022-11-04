<?php
namespace Admin\Controller;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

use Doctrine\Common\Collections\ArrayCollection;
use DoctrineModule\Paginator\Adapter\Collection as CollectionAdapter;
use Laminas\Paginator\Paginator;
use Interop\Container\ContainerInterface;

class UsersController extends AbstractActionController
{

    private $em;
    private $config;
    private $route;
    private $serviceManager;

    public function __construct($em, $config)
    {
        $this->em = $em;
        $this->config = $config;

        $this->route = 'admin/users';
    }

    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function indexAction()
    {
        $doctrineCollection = new ArrayCollection($this->fetchAll());
        $adapter = new CollectionAdapter($doctrineCollection);
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p', 1))->setItemCountPerPage(30);

        return new ViewModel([
            'title' => 'Usuarios',
            'results' => $paginator,
            'route' => $this->route
        ]);
    }

    public function addAction()
    {
        $form = new \Admin\Form\Users('create', $this->em, $this->config['authModule']['userEntity']);
        $request = $this->getRequest();


        $success = true;
        if($request->isPost()){
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $form->setData($post);

            if($form->isValid()){

                try {
                    $userManager = $this->serviceManager->get($this->config['authModule']['userManager']);
                    $post['rank_id'] = $this->em->find('Juliangorge\Users\Entity\UserRank', $post['rank_id']);
                    $userManager->addUser($post);

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
                return $this->redirect()->toRoute($this->route);
            }
        }

        return new ViewModel([
            'form' => $form,
            'title' => 'Usuario',
            'route' => $this->route
        ]);
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find($this->config['authModule']['userEntity'], $id);
        if($entity == NULL) return $this->redirect()->toRoute($this->route);

        $form = new \Admin\Form\Users('update', $this->em, $this->config['authModule']['userEntity'], $entity);
        $form->bind($entity);

        $request = $this->getRequest();

        $success = true;
        if($request->isPost()){
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $form->setData($post);

            if($form->isValid()){

                try {
                    $userManager = $this->serviceManager->get($this->config['authModule']['userManager']);
                    $post['rank_id'] = $this->em->find('Juliangorge\Users\Entity\UserRank', $post['rank_id']);
                    $post['status'] = $entity->getStatus();
                    $userManager->updateUser($entity, $post);

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
                return $this->redirect()->toRoute($this->route, []);
            }
        }

        return new ViewModel([
            'form' => $form,
            'title' => 'Usuario',
            'route' => $this->route,
            'item' => $entity
        ]);
    }

    public function deleteAction(){
        if(!$this->getRequest()->isPost()) return $this->redirect()->toRoute($this->route);

        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find($this->config['authModule']['userEntity'], $id);
        if($entity == NULL) return $this->redirect()->toRoute($this->route);

        $this->em->remove($entity);
        $this->em->flush();

        $this->flashMessenger()->addSuccessMessage('Borrado exitoso');
        return $this->redirect()->toRoute($this->route);
    }

    public function changePasswordAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find($this->config['authModule']['userEntity'], $id);
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
                return $this->redirect()->toRoute($this->route);
            }
        }

        return new ViewModel([
            'form' => $form,
            'title' => 'Cambiar contraseña',
            'route' => $this->route,
            'item' => $entity
        ]);
    }

    private function fetchAll($as_array = false)
    {
        return $this->em->createQuery('SELECT i FROM ' . $this->config['authModule']['userEntity'] . ' i ORDER BY i.id DESC')->getResult($as_array ? \Doctrine\ORM\Query::HYDRATE_ARRAY : NULL);
    }

}