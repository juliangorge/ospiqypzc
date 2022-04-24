<?php
namespace Admin\Controller;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

class AffiliatesPrescriptionsController extends AbstractActionController
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

        $this->collection = 'affiliates_prescriptions';
        $this->route = 'admin/affiliates_prescriptions';
    }

    public function indexAction(){
        return new ViewModel([
            'title' => 'Prescripciones'
        ]);
    }

    private function validarMedicamentos(&$form, &$post){
        $success = true;
        $tmp = $this->em->getRepository('Admin\Entity\Vademecum')->findOneBy(['id' => $post['first_medication_id'], 'region_id' => $post['region_id']]);
        if($post['first_medication'] != '' && $tmp == NULL){
            $form->get('first_medication')->setMessages(['Campo no válido y obligatorio']);
            $success = false;
        }else{
            $post['first_medication'] = trim($post['first_medication_id']);
        }

        $tmp = $this->em->getRepository('Admin\Entity\Vademecum')->findOneBy(['id' => $post['second_medication_id'], 'region_id' => $post['region_id']]);
        if($post['second_medication'] != '' && $tmp == NULL){
            $form->get('second_medication')->setMessages(['Campo no válido']);
            $success = false;
        }else{
            $post['second_medication'] = trim($post['second_medication_id']);
        }

        $tmp = $this->em->getRepository('Admin\Entity\Vademecum')->findOneBy(['id' => $post['third_medication_id'], 'region_id' => $post['region_id']]);
        if($post['third_medication'] != '' && $tmp == NULL){
            $form->get('third_medication')->setMessages(['Campo no válido']);
            $success = false;
        }else{
            $post['third_medication'] = trim($post['third_medication_id']);
        }

        return $success;
    }

    private function recargarForm(&$form, $entity){
        if($entity->getFirstMedication() != NULL){
            $tmp = $this->em->find('Admin\Entity\Vademecum', $entity->getFirstMedication());
            $form->get('first_medication')->setValue($tmp->getDrug() . ' ' . $tmp->getPresentation());
            $form->get('first_medication_id')->setValue($tmp->getId());
        }

        if($entity->getSecondMedication() != NULL){
            $tmp = $this->em->find('Admin\Entity\Vademecum', $entity->getSecondMedication());
            $form->get('second_medication')->setValue($tmp->getDrug() . ' ' . $tmp->getPresentation());
            $form->get('second_medication_id')->setValue($tmp->getId());
        }

        if($entity->getThirdMedication() != NULL){
            $tmp = $this->em->find('Admin\Entity\Vademecum', $entity->getThirdMedication());
            $form->get('third_medication')->setValue($tmp->getDrug() . ' ' . $tmp->getPresentation());
            $form->get('third_medication_id')->setValue($tmp->getId());
        }
    }

    public function addAction(){
        $form = new \Admin\Form\AffiliatePrescription($this->em);
        $request = $this->getRequest();

        $success = true;
        if($request->isPost()){
            $data = $request->getPost()->toArray();

            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $form->setData($post);

            if($form->isValid() && $this->validarMedicamentos($form, $post)){

                $entity = new \Admin\Entity\AffiliatePrescription();
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
                    $professional = $this->em->find('Admin\Entity\Professional', $entity->getProfessionalId());
                    $to_firebase['professional_name'] = $professional->getFirstname() . ' ' . $professional->getLastname();

                    $gender = $this->em->find('Admin\Entity\Gender', $entity->getGenderId());
                    $to_firebase['gender'] = $gender->getName();

                    unset($to_firebase['professional_id']);
                    unset($to_firebase['gender_id']);

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
            'title' => 'Prescripción'
        ]);
    }

    public function editAction(){
        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find('Admin\Entity\AffiliatePrescription', $id);
        if($entity == NULL) return $this->redirect()->toRoute($this->route);

        $form = new \Admin\Form\AffiliatePrescription($this->em, null, true);
        $form->bind($entity);
        $this->recargarForm($form, $entity);

        $request = $this->getRequest();

        $success = true;
        if($request->isPost()){
            $data = $request->getPost()->toArray();

            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $form->setData($post);

            if($form->isValid() && $this->validarMedicamentos($form, $post)){

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
                $professional = $this->em->find('Admin\Entity\Professional', $entity->getProfessionalId());
                $to_firebase['professional_name'] = $professional->getFirstname() . ' ' . $professional->getLastname();

                $gender = $this->em->find('Admin\Entity\Gender', $entity->getGenderId());
                $to_firebase['gender'] = $gender->getName();

                unset($to_firebase['professional_id']);
                unset($to_firebase['gender_id']);
                
                $docRef = $this->firestore->collection($this->collection)->document($entity->getDocumentId());
                $docRef->set($to_firebase, ['merge' => true]);

                $this->flashMessenger()->addSuccessMessage('Carga exitosa');
                return $this->redirect()->toRoute($route, [], $query);
            }
        }

        return new ViewModel([
            'form' => $form,
            'title' => 'Prescripción',
            'item' => $entity
        ]);
    }

    public function deleteAction(){
        if($this->params()->fromRoute('id') === null) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find('Admin\Entity\AffiliatePrescription', $this->params()->fromRoute('id'));

        if($entity == null) return $this->redirect()->toRoute($this->route);

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
            return $this->em->createQuery('SELECT a FROM Admin\Entity\AffiliatePrescription a WHERE a.id = :id')->setParameter('id', $id)->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }else{

            return $this->em->createQuery('
                SELECT 
                c.id, c.fullname, c.age, c.dni, g.name as gender_name, s.name as specialty_name, c.appointment_date, c.expiration_date, 
                CONCAT(p.firstname, \' \', p.lastname) as professional_name
                FROM Admin\Entity\AffiliatePrescription c 
                JOIN Admin\Entity\Professional p WITH p.id = c.professional_id
                JOIN Admin\Entity\Specialty s WITH s.id = p.specialty_id
                JOIN Admin\Entity\Gender g WITH g.id = c.gender_id
                ORDER BY c.id ASC')->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }
    }

}