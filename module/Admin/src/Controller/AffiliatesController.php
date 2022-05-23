<?php
namespace Admin\Controller;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

class AffiliatesController extends AbstractActionController
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

        $this->collection = 'affiliates_data';
        $this->route = 'admin/affiliates';
    }

    public function indexAction(){
        return new ViewModel([
            'title' => 'Afiliados'
        ]);
    }

    /*
    Migración:

    $affiliates = $this->em->createQuery('SELECT a FROM Admin\Entity\Affiliate a WHERE a.document_id IS NULL')->getResult();
    foreach($affiliates as $a){
        $to_firebase = $a->toFirebase();
        $documentReference = $this->firestore->collection($this->collection)->add($to_firebase);
        $a->setDocumentId($documentReference->id());
    }
    $this->em->flush();
    die;
    */

    private function checkIfDniAlreadyExists($dni){
        $entity = $this->em->getRepository('Admin\Entity\Affiliate')->findOneBy(['dni' => $dni]);
        if($entity != NULL){
            throw new \Exception('Ya existe el DNI ingresado');
        }
    }

    public function addAction(){
        $form = new \Admin\Form\Affiliate($this->em);
        $request = $this->getRequest();

        $success = true;
        if($request->isPost()){
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $form->setData($post);

            if($form->isValid()){
                $entity = new \Admin\Entity\Affiliate();
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
                    $to_firebase = $entity->toFirebase(true);
                    $documentReference = $this->firestore->collection($this->collection)->add($to_firebase);
                    $entity->setDocumentId($documentReference->id());

                    $documentReferenceDni = $this->firestore->collection('affiliates_dni')->add([
                        'dni' => $to_firebase['dni'],
                        'name' => $to_firebase['name']
                    ]);
                    
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
            'title' => 'Afiliado'
        ]);
    }

    public function editAction(){
        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find('Admin\Entity\Affiliate', $id);
        $old_dni = $entity->getDni();
        if($entity == NULL) return $this->redirect()->toRoute($this->route);

        $form = new \Admin\Form\Affiliate($this->em);
        $form->bind($entity);

        $request = $this->getRequest();

        $success = true;
        if($request->isPost()){
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $form->setData($post);

            if($form->isValid()){
                try {
                    $this->checkIfDniAlreadyExists($post['dni']);
                    $entity->exchangeArray($post);
                    $this->em->flush();
                }catch(\Throwable $e){
                    $this->layout()->errorMessage = $e->getMessage();
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

                $affiliate_dni = $this->firestore->collection('affiliates_dni')->where('dni', '=', $old_dni);
                $documents = $affiliate_dni->documents();

                foreach ($documents as $document) {
                    $docRef = $this->firestore->collection('affiliates_dni')->document($document->id());
                    $docRef->set([
                        'dni' => $to_firebase['dni'],
                        'name' => $to_firebase['name'],
                    ], ['merge' => true]);
                }

                $this->flashMessenger()->addSuccessMessage('Carga exitosa');
                return $this->redirect()->toRoute($route, [], $query);
            }
        }

        return new ViewModel([
            'form' => $form,
            'title' => 'Afiliado',
            'item' => $entity
        ]);
    }

    public function deleteAction(){
        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find('Admin\Entity\Affiliate', $id);

        if($entity == null) return $this->redirect()->toRoute($this->route);

        $this->firestore->collection($this->collection)->document($entity->getDocumentId())->delete();

        $this->em->remove($entity);
        $this->em->flush();
        return $this->redirect()->toRoute($this->route);
    }

    public function getByDniAction(){
        return new JsonModel($this->em->createQuery('SELECT a FROM Admin\Entity\Affiliate a WHERE a.dni = :dni')->setParameters(['dni' => $this->params()->fromRoute('id', 0)])->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY));
    }

    public function getAction(){
        return new JsonModel($this->get($this->params()->fromRoute('id', 0)));
    }

    public function get($id = false){
        if($id){
            return $this->em->createQuery('SELECT a FROM Admin\Entity\Affiliate a WHERE a.id = :id')->setParameter('id', $id)->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }else{
            return $this->em->createQuery('SELECT a FROM Admin\Entity\Affiliate a ORDER BY a.lastname ASC')->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }
    }

    // Retorna resultados en base al DNI en Afiliados y Familiares
    public function getTotalByDniAction(){
        $dni = $this->params()->fromRoute('id', 0);

        $affiliate = $this->em->createQuery('SELECT a FROM Admin\Entity\Affiliate a WHERE a.dni = :dni')->setParameters(['dni' => $dni])->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        if($affiliate != NULL)
        {
            return new JsonModel($affiliate);
        }
        else{
            return new JsonModel($this->em->createQuery('SELECT a FROM Admin\Entity\AffiliateFamily a WHERE a.dni = :dni')->setParameters(['dni' => $dni])->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY));
        }
    }

    public function importAction()
    {
        $whitelist = ['127.0.0.1', '::1'];
        if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
            return new JsonModel(['success' => false]);
        }

        $import = new \Admin\Controller\Import($this->em);

        try {
            $import->initialize();
        }
        catch(\Throwable $e){
            throw new \Exception('Ocurrió un error: ' . $e->getMessage());
        }

        return new JsonModel(['success' => true]);
    }

}