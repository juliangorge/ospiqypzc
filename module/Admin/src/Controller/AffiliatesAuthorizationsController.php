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

    public function authorizingAction(){
        $id = $this->params()->fromRoute('id', 0);
        $is_approved = $this->params()->fromQuery('is_approved', 0);

        $entity = $this->em->find('Admin\Entity\AffiliateAuthorization', $id);
        if($entity == NULL) return $this->redirect()->toRoute('admin/affiliates_authorizations');
        if($entity->getAuthorizationDate() != NULL) return $this->redirect()->toRoute('admin/affiliates_authorizations');

        $entity->authorizing([
            'is_approved' => $is_approved,
            'user_id' => $this->identity()['id']
        ]);
        $this->em->flush();

        $user = $this->em->find('Auth\Entity\User', $entity->getUserId());
        if($user == NULL) return $this->redirect()->toRoute('admin/affiliates_authorizations');        

        $data = [
            'authorization_date' => $entity->getAuthorizationDate()->format('d/m/Y'),
            'authorizing_administrative' => $user->getFirstname() . ' ' . $user->getLastname(),
            'is_approved' => $entity->getIsApproved() ? true : false
        ];

        $docRef = $this->firestore->collection($this->collection)->document($entity->getDocumentId());
        $docRef->set($data, ['merge' => true]);

        $this->flashMessenger()->addSuccessMessage('Carga exitosa');
        return $this->redirect()->toRoute('admin/affiliates_authorizations');
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