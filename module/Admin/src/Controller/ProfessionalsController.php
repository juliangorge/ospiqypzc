<?php
namespace Admin\Controller;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

class ProfessionalsController extends AbstractActionController
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

        $this->collection = 'professionals';
        $this->route = 'admin/professionals';
    }

    public function indexAction(){
        return new ViewModel([
            'title' => 'Profesionales'
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

                $entity = new \Admin\Entity\Professional();
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
                    $specialty = $this->em->find('Admin\Entity\Specialty', $entity->getSpecialtyId());
                    $to_firebase['specialty'] = $specialty->getName();

                    $type_of_attention = $this->em->find('Admin\Entity\TypeOfAttention', $entity->getTypeOfAttentionId());
                    $to_firebase['type_of_attention'] = $type_of_attention->getName();

                    unset($to_firebase['specialty_id']);
                    unset($to_firebase['type_of_attention_id']);

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
            'title' => 'Profesional'
        ]);
    }

    public function editAction(){
        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find('Admin\Entity\Professional', $id);
        if($entity == NULL) return $this->redirect()->toRoute($this->route);

        $form = new \Admin\Form\Professional($this->em);
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
                $specialty = $this->em->find('Admin\Entity\Specialty', $entity->getSpecialtyId());
                $to_firebase['specialty'] = $specialty->getName();

                $type_of_attention = $this->em->find('Admin\Entity\TypeOfAttention', $entity->getTypeOfAttentionId());
                $to_firebase['type_of_attention'] = $type_of_attention->getName();

                unset($to_firebase['specialty_id']);
                unset($to_firebase['type_of_attention_id']);

                $docRef = $this->firestore->collection($this->collection)->document($entity->getDocumentId());
                $docRef->set($to_firebase, ['merge' => true]);

                $this->flashMessenger()->addSuccessMessage('Carga exitosa');
                return $this->redirect()->toRoute($route, [], $query);
            }
        }

        return new ViewModel([
            'form' => $form,
            'title' => 'Profesional',
            'item' => $entity
        ]);
    }

    public function deleteAction(){
        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find('Admin\Entity\Professional', $id);
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
            return $this->em->createQuery('SELECT a FROM Admin\Entity\Professional a WHERE a.id = :id')->setParameter('id', $id)->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }else{
            return $this->em->createQuery('
                SELECT c.id, c.firstname, c.lastname, c.dni, s.name as specialty_name, t.name as type_of_attention 
                FROM Admin\Entity\Professional c 
                JOIN Admin\Entity\Specialty s WITH s.id = c.specialty_id
                JOIN Admin\Entity\TypeOfAttention t WITH t.id = c.type_of_attention_id
                ORDER BY c.lastname ASC')->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }
    }

}