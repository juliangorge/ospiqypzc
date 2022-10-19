<?php
namespace Admin\Controller;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

use Google\Cloud\Firestore\FirestoreClient;
use Doctrine\Common\Collections\ArrayCollection;
use DoctrineModule\Paginator\Adapter\Collection as CollectionAdapter;
use Laminas\Paginator\Paginator;

class NewsController extends AbstractActionController
{

    private $em;
    private $config;
    private $firestore;
    private $colection;
    private $route;

    public function __construct($em, $config){
        $this->em = $em;
        $this->config = $config;

        $this->firestore = new FirestoreClient([
            'projectId' => $this->config['firestore_projectId'],
            'keyFilePath' => $this->config['firestore_keyFilePath']
        ]);

        $this->collection = 'news';
        $this->route = 'admin/news';
    }

    public function indexAction()
    {
        $doctrineCollection = new ArrayCollection($this->fetchAll());
        $adapter = new CollectionAdapter($doctrineCollection);
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p', 1))->setItemCountPerPage(30);

        return new ViewModel([
            'title' => 'Noticias',
            'results' => $paginator,
            'route' => $this->route
        ]);
    }

    public function addAction(){
        $form = new \Admin\Form\News($this->em);
        $request = $this->getRequest();

        $success = true;
        if($request->isPost()){
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $form->setData($post);

            if($form->isValid()){

                $entity = new \Admin\Entity\News();
                $entity->initialize($post);
                $this->em->persist($entity);

                try {
                    $this->em->flush();
                }catch(\Throwable $e){
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                    $success = false;
                }

                if($success){                
                    
                    $to_firebase = $entity->toFirebase();
                    $documentReference = $this->firestore->collection($this->collection)->add($to_firebase);
                    $entity->setDocumentId($documentReference->id());
                    
                    try {
                        $this->em->flush();
                    }catch(\Throwable $e){
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                        $success = false;   
                    }
                    
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
            'title' => 'Noticia',
            'route' => $this->route
        ]);
    }

    public function editAction(){
        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find('Admin\Entity\News', $id);
        if($entity == NULL) return $this->redirect()->toRoute($this->route);

        $form = new \Admin\Form\News($this->em);
        $form->bind($entity);

        $request = $this->getRequest();

        $success = true;
        if($request->isPost()){
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $form->setData($post);

            if($form->isValid()){

                try {
                    $entity->exchangeArray($post);
                    $this->em->flush();
                }catch(\Throwable $e){
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                    $success = false;
                }

            }else{
                $this->flashMessenger()->addErrorMessage($form->getMessages());
                $success = false;
            }
            
            if($success){
                
                $to_firebase = $entity->toFirebase();
                $docRef = $this->firestore->collection($this->collection)->document($entity->getDocumentId());
                $docRef->set($to_firebase, ['merge' => true]);

                $this->flashMessenger()->addSuccessMessage('Carga exitosa');
                return $this->redirect()->toRoute($this->route);
            }
        }

        return new ViewModel([
            'form' => $form,
            'title' => 'Noticia',
            'route' => $this->route,
            'item' => $entity
        ]);
    }

    public function deleteAction(){
        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find('Admin\Entity\News', $id);
        if($entity == NULL) return $this->redirect()->toRoute($this->route);

        $this->firestore->collection($this->collection)->document($entity->getDocumentId())->delete();

        $this->em->remove($entity);
        $this->em->flush();
                
        $this->flashMessenger()->addSuccessMessage('Borrado exitoso');
        return $this->redirect()->toRoute($this->route);
    }

    private function fetchAll($as_array = false){
        return $this->em->createQuery('SELECT i FROM Admin\Entity\News i ORDER BY i.id DESC')->getResult($as_array ? \Doctrine\ORM\Query::HYDRATE_ARRAY : NULL);
    }

}