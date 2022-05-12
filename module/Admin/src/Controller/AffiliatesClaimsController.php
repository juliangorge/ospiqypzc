<?php
namespace Admin\Controller;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

class AffiliatesClaimsController extends AbstractActionController
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

        $this->collection = 'affiliates_claims';
        $this->route = 'admin/affiliates_claims';
    }

    public function indexAction(){
        return new ViewModel([
            'title' => 'Reclamos'
        ]);
    }

    public function answerAction(){
        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find('Admin\Entity\AffiliateClaim', $id);
        if($entity == NULL) return $this->redirect()->toRoute($this->route);

        $readonly = $entity->getDateAnswer() == NULL;
        $form = new \Admin\Form\AffiliateClaim($this->em, NULL, $readonly);
        $form->bind($entity);

        $affiliate = $this->em->getRepository('Admin\Entity\Affiliate')->findOneBy(['dni' => $entity->getDni()]);
        if($affiliate == NULL) return $this->redirect()->toRoute($this->route);
        $form->get('affiliate_fullname')->setValue($affiliate->getFirstname() . ' ' . $affiliate->getLastname());

        $request = $this->getRequest();

        $success = true;
        if($request->isPost()){
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $form->setData($post);

            if($form->isValid()){
                try {
                    if($entity->getDateAnswer() == NULL){
                        $entity->exchangeArray($post);
                        $this->em->flush();
                    }
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
                $user = $this->em->find('Auth\Entity\User', $entity->getUserId());
                $to_firebase['authorization_administrative'] = $user->getFirstname() . ' ' . $user->getLastname();

                $docRef = $this->firestore->collection($this->collection)->document($entity->getDocumentId());
                $docRef->set($to_firebase, ['merge' => true]);

                $this->flashMessenger()->addSuccessMessage('Carga exitosa');
                return $this->redirect()->toRoute($route, [], $query);
            }
        }

        return new ViewModel([
            'form' => $form,
            'title' => 'Reclamo',
            'item' => $entity
        ]);
    }

    public function getAction(){
        return new JsonModel($this->get($this->params()->fromRoute('id', 0)));
    }

    public function get($id = false){
        if($id){
            return $this->em->createQuery('SELECT a FROM Admin\Entity\AffiliateClaim a WHERE a.id = :id')->setParameter('id', $id)->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }else{
            return $this->em->createQuery('
                SELECT c.id, c.title, c.detail, CONCAT(a.firstname, \' \', a.lastname) as fullname_affiliate, c.date_answer
                FROM Admin\Entity\AffiliateClaim c 
                INNER JOIN Admin\Entity\Affiliate a WITH a.dni = c.dni
                ORDER BY c.id ASC')->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }
    }

}