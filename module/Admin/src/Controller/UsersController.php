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
                    $post['role_id'] = $this->em->find('Juliangorge\Users\Entity\UserRole', $post['role_id']);
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
                    $post['role_id'] = $this->em->find('Juliangorge\Users\Entity\UserRole', $post['role_id']);
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

    public function privilegesByRoleAction(){
        $id = $this->params()->fromRoute('id', 0);
        $form = new \Admin\Form\UserPrivilegeByRole($this->em);
        $success = false;

        $entity = $this->em->find('Juliangorge\Users\Entity\UserRole', $id);
        if($entity == NULL) return $this->redirect()->toRoute($this->route, ['action' => 'roles']);

        $form->get('privileges')->setValue(array_column($entity->getPrivilegesArray(), 'id'));

        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost()->toArray();
            $form->setData($data);

            if($form->isValid()){
                $data = $form->getData();

                $data['privileges'] = $this->em->getRepository('Juliangorge\Users\Entity\UserPrivilege')
                    ->findBy(['id' => $data['privileges']]);
                $entity->addPrivileges($data['privileges']);

                $this->em->flush();
                $success = true;
            }else{
                $this->layout()->addErrorMessage = $form->getMessages();
            }
        }

        if($success){
            $this->flashMessenger()->addSuccessMessage('Cambios aplicados');
            return $this->redirect()->toRoute($this->route, ['action' => 'roles']);
        }

        return new ViewModel([
            'title' => 'Rol',
            'form' => $form,
            'route' => $this->route,
            'id' => $id
        ]);
    }

    public function roleAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        $form = new \Admin\Form\UserRole($this->em);
        $success = false;

        if($id){
            $entity = $this->em->find('Juliangorge\Users\Entity\UserRole', $id);
            if($entity == NULL) return $this->redirect()->toRoute($this->route, ['action' => 'roles']);
            $form->bind($entity);
        }

        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost()->toArray();
            $form->setData($data);

            if($form->isValid()){
                $data = $form->getData();
                if(!$id){
                    $role = new \Juliangorge\Users\Entity\UserRole($data);
                    $this->em->persist($role);
                }
                $this->em->flush();
                $success = true;
            }else{
                $this->layout()->addErrorMessage = $form->getMessages();
            }
        }

        if($success){
            $this->flashMessenger()->addSuccessMessage('Cambios aplicados');
            return $this->redirect()->toRoute($this->route, ['action' => 'roles']);
        }

        return new ViewModel([
            'title' => 'Rol',
            'form' => $form,
            'route' => $this->route,
            'id' => $id
        ]);
    }

    public function rolesAction()
    {
        return new ViewModel([
            'title' => 'Roles',
            'data' => $this->em->createQuery('SELECT a.id, a.name FROM Juliangorge\Users\Entity\UserRole a')->getResult(),
            'route' => $this->route
        ]);
    }

    public function privilegesAction()
    {
        return new ViewModel([
            'title' => 'Privilegios',
            'data' => $this->em->createQuery('SELECT a.id, a.name, a.functionality, a.action FROM Juliangorge\Users\Entity\UserPrivilege a')->getResult(),
            'route' => $this->route
        ]);
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