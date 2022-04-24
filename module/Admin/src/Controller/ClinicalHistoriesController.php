<?php
namespace Admin\Controller;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

class ClinicalHistoriesController extends AbstractActionController
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

        $this->collection = 'affiliates_clinic_history';
        $this->route = 'admin/clinical_histories';
    }

    public function indexAction(){
        return new ViewModel([
            'title' => 'Historias Clínicas'
        ]);
    }

    public function addAction(){
        $form = new \Admin\Form\ClinicalHistory($this->em);
        $request = $this->getRequest();

        $success = true;
        if($request->isPost()){
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $raw_data = $post;
            $form->setData($post);

            if($form->isValid()){

                $entity = new \Admin\Entity\ClinicalHistory();
                $entity->initialize($post);

                if($raw_data['file_number'] != '') $entity->setFileNumber($raw_data['file_number']);
                if($raw_data['diagnose'] != '') $entity->setDiagnose($raw_data['diagnose']);
                if($raw_data['observations'] != '') $entity->setObservations($raw_data['observations']);
                if($raw_data['treatment'] != '') $entity->setTreatment($raw_data['treatment']);

                $this->em->persist($entity);

                try {
                    $this->em->flush();
                }catch(\Throwable $e){
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                    $success = false;
                }

                if($success){                
                    
                    $to_firebase = $entity->toFirebase();

                    $professional = $this->em->find('Admin\Entity\Professional', $entity->getProfessionalId());
                    $to_firebase['professional'] = $professional->getLastname() . ', ' . $professional->getFirstname();

                    $specialty = $this->em->find('Admin\Entity\Specialty', $professional->getSpecialtyId());
                    $to_firebase['specialty'] = $specialty->getName();

                    unset($to_firebase['specialty_id']);
                    unset($to_firebase['profesional_id']);
                    
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

    public function deleteAction(){
        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find('Admin\Entity\ClinicalHistory', $id);
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
            return $this->em->createQuery('SELECT a FROM Admin\Entity\ClinicalHistory a WHERE a.id = :id')->setParameter('id', $id)->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }else{
            return $this->em->createQuery('
                SELECT c.id, c.fullname, c.dni, 
                s.name as specialty_name, CONCAT(p.lastname, \', \', p.firstname) as professional_fullname
                FROM Admin\Entity\ClinicalHistory c 
                JOIN Admin\Entity\Professional p WITH p.id = c.professional_id
                JOIN Admin\Entity\Specialty s WITH s.id = p.specialty_id
                ORDER BY c.id DESC')->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }
    }

}