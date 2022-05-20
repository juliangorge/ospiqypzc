<?php
namespace Admin\Controller;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

class AffiliatesFamilyController extends AbstractActionController
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

        $this->collection = 'affiliates_family';
        $this->route = 'admin/affiliates_family';
    }

    public function indexAction(){
        return new ViewModel([
            'title' => 'Familiares de Afiliados'
        ]);
    }

    /*
    Migración:

    $affiliates = $this->em->createQuery('SELECT a FROM Admin\Entity\AffiliateFamily a WHERE a.document_id IS NULL')->getResult();
    foreach($affiliates as $a){
        $to_firebase = $a->toFirebase();
        $type_of_family_member = $this->em->find('Admin\Entity\TypeOfFamilyMember', $to_firebase['type_of_family_member_id']);
        $to_firebase['type_of_family_member'] = $type_of_family_member->getName();

        $aff = $this->em->getRepository('Admin\Entity\Affiliate')->findOneBy(['dni' => $a->getAffiliateDni()]);
        if($aff == NULL) continue;
        $to_firebase['affiliate_number'] = strval($aff->getAffiliateType()) . '0' . strval($type_of_family_member->getId());
        unset($to_firebase['type_of_family_member_id']);

        $documentReference = $this->firestore->collection($this->collection)->add($to_firebase);
        $a->setDocumentId($documentReference->id());
    
        $this->em->flush();
    }
    die;
    */

    private function checkIfDniAlreadyExists($dni){
        $entity = $this->em->getRepository('Admin\Entity\Affiliate')->findOneBy(['dni' => $dni]);
        if($entity != NULL){
            throw new \Exception('Ya existe el DNI ingresado');
        }
    }

    public function addAction(){
        $form = new \Admin\Form\AffiliateFamily($this->em);
        $request = $this->getRequest();

        $success = true;
        if($request->isPost()){
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $form->setData($post);

            if($form->isValid()){

                $affiliate = $this->em->getRepository('Admin\Entity\Affiliate')->findOneBy(['dni' => $post['affiliate_dni']]);
                if($affiliate == NULL){
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                    $success = false;
                }else{
                    $entity = new \Admin\Entity\AffiliateFamily();
                    $post['region_id'] = $affiliate->getRegionId();
                    $entity->initialize($post);
                    $this->em->persist($entity);

                    try {
                        $this->checkIfDniAlreadyExists($post['dni']);
                        $this->em->flush();
                    }catch(\Throwable $e){
                        $this->layout()->errorMessage = $e->getMessage();
                        $success = false;
                    }

                    if($success){
                        $to_firebase = $entity->toFirebase();
                        
                        $type_of_family_member = $this->em->find('Admin\Entity\TypeOfFamilyMember', $to_firebase['type_of_family_member_id']);
                        $to_firebase['type_of_family_member'] = $type_of_family_member->getName();

                        $to_firebase['affiliate_number'] = strval($affiliate->getAffiliateType()) . '0' . strval($type_of_family_member->getId());
                        unset($to_firebase['type_of_family_member_id']);

                        $documentReference = $this->firestore->collection($this->collection)->add($to_firebase);
                        $entity->setDocumentId($documentReference->id());
                        
                        try {
                            $this->em->flush();
                        }catch(\Throwable $e){
                            $this->flashMessenger()->addErrorMessage($e->getMessage());
                            $success = false;   
                        }
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
            'title' => 'Familiar de Afiliado'
        ]);
    }

    public function editAction(){
        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find('Admin\Entity\AffiliateFamily', $id);
        if($entity == NULL) return $this->redirect()->toRoute($this->route);

        $form = new \Admin\Form\AffiliateFamily($this->em);
        $form->bind($entity);

        $request = $this->getRequest();

        $success = true;
        if($request->isPost()){
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $form->setData($post);

            if($form->isValid()){

                $affiliate = $this->em->getRepository('Admin\Entity\Affiliate')->findOneBy(['dni' => $post['affiliate_dni']]);
                if($affiliate == NULL){
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                    $success = false;
                }else{
                    try {
                        $this->checkIfDniAlreadyExists($post['dni']);
                        $post['region_id'] = $affiliate->getRegionId();
                        $entity->exchangeArray($post);
                        $this->em->flush();
                    }catch(\Throwable $e){
                        $this->layout()->errorMessage = $e->getMessage();
                        $success = false;
                    }
                }

            }else{
                $this->flashMessenger()->addErrorMessage($form->getMessages());
                $success = false;
            }

            if($success){
                $to_firebase = $entity->toFirebase();
                        
                $type_of_family_member = $this->em->find('Admin\Entity\TypeOfFamilyMember', $to_firebase['type_of_family_member_id']);
                $to_firebase['type_of_family_member'] = $type_of_family_member->getName();

                $to_firebase['affiliate_number'] = strval($affiliate->getAffiliateType()) . '0' . strval($type_of_family_member->getId());
                unset($to_firebase['type_of_family_member_id']);
                $docRef = $this->firestore->collection($this->collection)->document($entity->getDocumentId());
                $docRef->set($to_firebase, ['merge' => true]);

                $this->flashMessenger()->addSuccessMessage('Carga exitosa');
                return $this->redirect()->toRoute($route, [], $query);
            }
        }

        return new ViewModel([
            'form' => $form,
            'title' => 'Familiar de Afiliado',
            'item' => $entity
        ]);
    }

    public function deleteAction(){
        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find('Admin\Entity\AffiliateFamily', $id);
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
            return $this->em->createQuery('SELECT a FROM Admin\Entity\AffiliateFamily a WHERE a.id = :id')->setParameter('id', $id)->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }else{
            return $this->em->createQuery('
                SELECT a.id, a.firstname, a.lastname, a.dni, a.email, a.affiliate_dni, m.name as type_of_family_member
                FROM Admin\Entity\AffiliateFamily a 
                JOIN Admin\Entity\TypeOfFamilyMember m WITH m.id = a.type_of_family_member_id

                ORDER BY a.lastname ASC')->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }
    }


}