<?php
namespace Admin\Controller;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

class VademecumController extends AbstractActionController
{
    private $em;

    private $config;

    private $firestore;

    private $collection;

    private $route;

    public function __construct($em, $config){
        $this->em = $em;
        $this->config = $config;

        $this->firestore = new \Google\Cloud\Firestore\FirestoreClient([
            'projectId' => 'ospiqyp-oridhean',
            'keyFilePath' => './ospiqyp-firebase-adminsdk-dx723-21cf738448.json'
        ]);

        $this->collection = 'vademecum';
        $this->route = 'admin/vademecum';
    }

    public function indexAction(){
        return new ViewModel([
            'title' => 'Vademecum'
        ]);
    }

    public function addAction(){
        $form = new \Admin\Form\Vademecum($this->em);
        $request = $this->getRequest();

        $success = true;
        if($request->isPost()){
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $raw_data = $post;
            $form->setData($post);

            if($form->isValid()){

                $entity = new \Admin\Entity\Vademecum();
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
                return $this->redirect()->toRoute($route, [], $query);
            }
        }

        return new ViewModel([
            'form' => $form,
            'title' => 'Historia Clínica'
        ]);
    }

    public function editAction(){
        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find('Admin\Entity\Vademecum', $id);
        if($entity == NULL) return $this->redirect()->toRoute($this->route);

        $form = new \Admin\Form\Vademecum($this->em);
        $form->bind($entity);

        $request = $this->getRequest();

        $success = true;
        if($request->isPost()){
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $raw_data = $post;

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
                return $this->redirect()->toRoute($route, [], $query);
            }
        }

        return new ViewModel([
            'form' => $form,
            'title' => 'Historia Clínica',
            'item' => $entity
        ]);
    }

    public function deleteAction(){
        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find('Admin\Entity\Vademecum', $id);
        if($entity == NULL) return $this->redirect()->toRoute($this->route);

        $this->firestore->collection($this->collection)->document($entity->getDocumentId())->delete();

        $this->em->remove($entity);
        $this->em->flush();
        return $this->redirect()->toRoute($this->route);
    }

    public function getAction(){
        return new JsonModel($this->get($this->params()->fromRoute('id', 0)));
    }

    public function get($id = false){
        if($id){
            return $this->em->createQuery('SELECT v FROM Admin\Entity\Vademecum v WHERE v.id = :id')->setParameter('id', $id)->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }else{
            return $this->em->createQuery('
                SELECT v.id, v.drug, v.presentation, r.name as region_name
                FROM Admin\Entity\Vademecum v 
                INNER JOIN Admin\Entity\Region r WITH r.id = v.region_id
                ORDER BY v.id DESC')->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }
    }

    public function getByRegionIdAction(){
        $region_id = $this->params()->fromRoute('id', 0);
        $label = $this->params()->fromQuery('search', 0);

        return new JsonModel($this->em->createQuery('SELECT a.id, CONCAT(a.drug, \' - \', a.presentation) as label, a.region_id, a.document_id FROM Admin\Entity\Vademecum a WHERE a.region_id = :region_id AND (a.drug LIKE :label OR a.presentation LIKE :label)')->setParameters(['region_id' => $region_id, 'label' => '%' . $label . '%'])->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY));
    }
}