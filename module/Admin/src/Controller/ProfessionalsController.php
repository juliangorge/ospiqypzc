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

class ProfessionalsController extends AbstractActionController
{

    protected $em;
    protected $sm;
    protected $config;
    protected $firestore;
    protected $colection;
    protected $route;

    public function __construct($em, $sm){
        $this->em = $em;
        $this->sm = $sm;
        $this->config = $sm->get('config');

        $this->firestore = new FirestoreClient([
            'projectId' => $this->config['firestore_projectId'],
            'keyFilePath' => $this->config['firestore_keyFilePath']
        ]);

        $this->collection = 'professionals';
        $this->route = 'admin/professionals';
    }

    public function indexAction()
    {
        $doctrineCollection = new ArrayCollection($this->fetchAll());
        $adapter = new CollectionAdapter($doctrineCollection);
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p', 1))->setItemCountPerPage(30);

        return new ViewModel([
            'title' => 'Profesionales',
            'results' => $paginator,
            'route' => $this->route
        ]);
    }

    public function addAction(){
        $form = new \Admin\Form\Professional($this->em);
        $request = $this->getRequest();

        $success = true;
        if($request->isPost()){
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $form->setData($post);

            if($form->isValid()){

                $post['specialties'] = $this->em->getRepository('Admin\Entity\Specialty')
                        ->findBy(['id' => $post['specialties']]);
                $entity = new \Admin\Entity\Professional($post);
                $this->em->persist($entity);

                try {
                    $this->em->flush();
                }catch(\Throwable $e){
                    $this->layout()->errorMessage = $e->getMessage();
                    $success = false;
                }

                if($success){                
                    /*$to_firebase = $entity->toFirebase();
                    $documentReference = $this->firestore->collection($this->collection)->add($to_firebase);
                    $entity->setDocumentId($documentReference->id());
                    
                    try {
                        $this->em->flush();
                    }catch(\Throwable $e){
                        $this->layout()->errorMessage = $e->getMessage();
                        $success = false;   
                    }*/
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
            'title' => 'Profesional',
            'route' => $this->route
        ]);
    }

    public function editAction(){
        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find('Admin\Entity\Professional', $id);
        if($entity == NULL) return $this->redirect()->toRoute($this->route);

        $form = new \Admin\Form\Professional($this->em);
        $form->bind($entity);
        $form->get('specialties')->setValue(array_column($entity->getSpecialtiesArray(), 'id'));

        $request = $this->getRequest();

        $success = true;
        if($request->isPost()){
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $form->setData($post);

            if($form->isValid()){

                try {
                    $post['specialties'] = $this->em->getRepository('Admin\Entity\Specialty')
                        ->findBy(['id' => $post['specialties']]);
                    $entity->exchangeArray($post);
                    $this->em->flush();
                }catch(\Throwable $e){
                    $this->layout()->errorMessage = $e->getMessage();
                    $success = false;
                }

            }else{
                $this->layout()->errorMessage = $e->getMessage();
                $success = false;
            }
            
            if($success){
                
                #$to_firebase = $entity->toFirebase();
                #$docRef = $this->firestore->collection($this->collection)->document($entity->getDocumentId());
                #$docRef->set($to_firebase, ['merge' => true]);

                $this->flashMessenger()->addSuccessMessage('Carga exitosa');
                return $this->redirect()->toRoute($this->route);
            }
        }

        return new ViewModel([
            'form' => $form,
            'title' => 'Profesional',
            'route' => $this->route,
            'item' => $entity
        ]);
    }

    /*public function deleteAction(){
        if(!$this->getRequest()->isPost()) return $this->redirect()->toRoute($this->route);

        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find('Admin\Entity\Professional', $id);
        if($entity == NULL) return $this->redirect()->toRoute($this->route);

        if($entity->getDocumentId() != NULL){
            $this->firestore->collection($this->collection)->document($entity->getDocumentId())->delete();
        }

        $this->em->remove($entity);
        $this->em->flush();
                
        $this->flashMessenger()->addSuccessMessage('Borrado exitoso');
        return $this->redirect()->toRoute($this->route);
    }*/

    public function getBySpecialtiesAction(){
        $request = $this->getRequest();
        if(!$request->isPost()){
            header('HTTP/1.0 401');
            exit;
        }

        $data = $request->getPost()->toArray();
        $professionals = $this->em->getRepository('Admin\Entity\Professional')->findAll();

        $array = [];
        foreach($professionals as $i){
            if($i->hasSpecialty($data['specialty_id'])) $array[] = [
                'id' => $i->getId(),
                'full_name' => $i->getFullName(),
            ];
        }

        return new JsonModel($array);
    }

    private function fetchAll($as_array = false){
        return $this->em->getRepository('Admin\Entity\Professional')->findAll();
    }
}