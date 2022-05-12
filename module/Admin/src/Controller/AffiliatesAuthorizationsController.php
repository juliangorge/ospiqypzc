<?php
namespace Admin\Controller;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

class AffiliatesAuthorizationsController extends AbstractActionController
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

        $this->collection = 'affiliates_authorizations';
        $this->route = 'admin/affiliates_authorizations';
    }

    public function indexAction(){
        return new ViewModel([
            'title' => 'Autorizaciones'
        ]);
    }

    public function downloadAction(){
        // syncronized
        return new JsonModel([]);
    }

    public function authorizeAction(){
        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find('Admin\Entity\AffiliateAuthorization', $id);
        if($entity == NULL) return $this->redirect()->toRoute($this->route);

        $readonly = $entity->getAuthorizationDate() == NULL;
        $form = new \Admin\Form\AffiliateAuthorization($this->em, NULL, $readonly);
        $form->bind($entity);

        $affiliate = $this->em->getRepository('Admin\Entity\Affiliate')->findOneBy(['dni' => $entity->getDni()]);
        if($affiliate == NULL) return $this->redirect()->toRoute($this->route);
        $form->get('affiliate_fullname')->setValue($affiliate->getFirstname() . ' ' . $affiliate->getLastname());

        if($entity->getUserId() != NULL){
            $user = $this->em->find('Auth\Entity\User', $entity->getUserId());
            $form->get('authorization_administrative')->setValue($user->getFirstname() . ' ' . $user->getLastname());
        }

        $request = $this->getRequest();

        $success = true;
        if($request->isPost()){
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $form->setData($post);

            if($form->isValid()){
                try {
                    if($entity->getAuthorizationDate() == NULL){
                        $post['user_id'] = $this->identity()['id'];
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
            'title' => 'Autorización',
            'item' => $entity
        ]);
    }

    public function getAction(){
        return new JsonModel($this->get($this->params()->fromRoute('id', 0)));
    }

    public function get($id = false){
        if($id){
            return $this->em->createQuery('
                SELECT v 
                FROM Admin\Entity\AffiliateAuthorization v 
                WHERE v.id = :id')->setParameter('id', $id)->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }else{
            $authorization = $this->em->createQuery('
                SELECT
                v.id, CONCAT(a.firstname, \' \', a.lastname) as affiliate_fullname, v.dni as affiliate_dni, v.authorization_date, v.is_approved, v.date_created, v.type_of_authorization, CONCAT(u.firstname, \' \', u.lastname) as user_fullname
                FROM Admin\Entity\AffiliateAuthorization v 
                INNER JOIN Admin\Entity\Affiliate a WITH a.dni = v.dni
                LEFT JOIN Auth\Entity\User u WITH u.id = v.user_id
                ORDER BY v.id DESC')->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            return $authorization;
        }
    }
}