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

class AffiliatesAuthorizationsController extends AbstractActionController
{

    private $em;
    private $config;
    private $firestore;
    private $colection;
    private $route;

    public function __construct($em, $config){
        $this->em = $em;
        $this->config = $config;

        $this->firestore = new FirestoreClient([
            'projectId' => $this->config['firestore_projectId'],
            'keyFilePath' => $this->config['firestore_keyFilePath']
        ]);

        $this->collection = 'affiliates_authorizations';
        $this->route = 'admin/affiliates_authorizations';
    }

    public function indexAction()
    {
        $doctrineCollection = new ArrayCollection($this->fetchAll());
        $adapter = new CollectionAdapter($doctrineCollection);
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p', 1))->setItemCountPerPage(30);

        return new ViewModel([
            'title' => 'Autorizaciones',
            'results' => $paginator,
            'route' => $this->route
        ]);
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find('Admin\Entity\AffiliatesAuthorizations', $id);
        if($entity == NULL) return $this->redirect()->toRoute($this->route);

        $readonly = $entity->getAuthorizationDate() == NULL;
        $form = new \Admin\Form\AffiliatesAuthorizations($this->em, NULL, $readonly);
        $form->bind($entity);

        $affiliate = $this->em->getRepository('Admin\Entity\Affiliates')->findOneBy(['dni' => $entity->getDni()]);
        if($affiliate == NULL){
            $affiliate = $this->em->getRepository('Admin\Entity\AffiliatesFamily')->findOneBy(['dni' => $entity->getDni()]);
            if($affiliate == NULL) return $this->redirect()->toRoute($this->route);
        }
        $form->get('affiliate_fullname')->setValue($affiliate->getFullName());

        if($entity->getUserId() != NULL){
            $user = $this->em->find('Juliangorge\Users\Entity\User', $entity->getUserId());
            $form->get('administrative_name')->setValue($user->getFullName());
        }

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

                $user = $this->em->find('Juliangorge\Users\Entity\User', $entity->getUserId());
                $to_firebase['administrative_name'] = $user->getFullName();

                $docRef = $this->firestore->collection($this->collection)->document($entity->getDocumentId());
                $docRef->set($to_firebase, ['merge' => true]);

                $this->flashMessenger()->addSuccessMessage('Carga exitosa');
                return $this->redirect()->toRoute($this->route);
            }
        }

        return new ViewModel([
            'form' => $form,
            'title' => 'Autorización',
            'item' => $entity,
            'route' => $this->route
        ]);
    }

    private function fetchAll($as_array = false){
        return $this->em->createQuery('
            SELECT
            i.id,
            i.dni,
            i.user_id,
            i.authorization_date,
            i.date_created,
            i.status,
            i.type_of_authorization,
            i.medical_order_image_url,
            i.complementary_studies_image_url,
            CONCAT(a.first_name, \' \', a.last_name) as full_name,
            CONCAT(u.first_name, \' \', u.last_name) as administrative_name
            FROM Admin\Entity\AffiliatesAuthorizations i 
            INNER JOIN Admin\Entity\Affiliates a WITH a.dni = i.dni
            LEFT JOIN Juliangorge\Users\Entity\User u WITH u.id = i.user_id
            ORDER BY i.id DESC
        ')->getResult($as_array ? \Doctrine\ORM\Query::HYDRATE_ARRAY : NULL);
    }

}