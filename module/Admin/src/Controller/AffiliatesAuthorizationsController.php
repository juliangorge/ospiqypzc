<?php
namespace Admin\Controller;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Storage\StorageClient;

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
            'route' => $this->route,
            'role' => $this->appPlugin()->getUserRole()
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
            $user = $this->em->find($this->config['authModule']['userEntity'], $entity->getUserId());
            $form->get('administrative_name')->setValue($user->getDisplayName());
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

                $user = $this->em->find($this->config['authModule']['userEntity'], $entity->getUserId());
                $to_firebase['administrative_name'] = $user->getDisplayName();

                $docRef = $this->firestore->collection($this->collection)->document($entity->getDocumentId());
                $docRef->set($to_firebase, ['merge' => true]);

                $this->flashMessenger()->addSuccessMessage('Carga exitosa');
                return $this->redirect()->toRoute($this->route);
            }
        }

        $images = $this->getImages($entity);

        return new ViewModel([
            'form' => $form,
            'title' => 'Autorización',
            'item' => $entity,
            'images' => $images,
            'route' => $this->route
        ]);
    }

    public function viewAction(){
        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->createQuery('
            SELECT
            i as authorization,
            (CASE WHEN a.dni IS NOT NULL THEN (CONCAT(a.first_name, \' \', a.last_name)) ELSE (CONCAT(f.first_name, \' \', f.last_name)) END) as full_name,
            CONCAT(u.first_name, \' \', u.last_name) as administrative_name
            FROM Admin\Entity\AffiliatesAuthorizations i 
            LEFT JOIN Admin\Entity\Affiliates a WITH a.dni = i.dni
            LEFT JOIN Admin\Entity\AffiliatesFamily f WITH f.dni = i.dni
            LEFT JOIN ' . $this->config['authModule']['userEntity'] . ' u WITH u.id = i.user_id
            WHERE i.id = :id
            ORDER BY i.id DESC
        ')->setParameters(['id' => $id])
        ->getOneOrNullResult();

        if($entity == NULL) return $this->redirect()->toRoute($this->route);

        $images = $this->getImages($entity['authorization']);

        return new ViewModel([
            'title' => 'Autorización',
            'item' => $entity,
            'images' => $images,
            'route' => $this->route
        ]);   
    }

    private function fetchAll($as_array = false){
        return $this->em->createQuery('
            SELECT
            i.id,
            i.authorization_id,
            i.dni,
            i.user_id,
            i.authorization_date,
            i.date_created,
            i.status,
            i.type_of_authorization,
            i.medical_order_image_url,
            i.complementary_studies_image_url,
            (CASE WHEN a.dni IS NOT NULL THEN (CONCAT(a.first_name, \' \', a.last_name)) ELSE (CONCAT(f.first_name, \' \', f.last_name)) END) as full_name,
            CONCAT(u.first_name, \' \', u.last_name) as administrative_name
            FROM Admin\Entity\AffiliatesAuthorizations i 
            LEFT JOIN Admin\Entity\Affiliates a WITH a.dni = i.dni
            LEFT JOIN Admin\Entity\AffiliatesFamily f WITH f.dni = i.dni
            LEFT JOIN ' . $this->config['authModule']['userEntity'] . ' u WITH u.id = i.user_id
            ORDER BY i.id DESC
        ')->getResult($as_array ? \Doctrine\ORM\Query::HYDRATE_ARRAY : NULL);
    }

    protected function getImages(object $entity){
        $storage = new StorageClient([
            'projectId' => $this->config['firestore_projectId'],
            'keyFilePath' => $this->config['firestore_keyFilePath']
        ]);

        $bucket = $storage->bucket('ospiqyp-oridhean.appspot.com');

        $medical_order_image = $entity->getAttachedImageFile($entity->getMedicalOrderImageUrl());
        $complementary_studies_image = $entity->getAttachedImageFile($entity->getComplementaryStudiesImageUrl());
        $images = [
            'medical_order_image' => $medical_order_image == NULL ? NULL : $bucket->object($medical_order_image),
            'complementary_studies_image' => $complementary_studies_image == NULL ? NULL : $bucket->object($complementary_studies_image),
        ];

        foreach($images as $key => $value){
            if($value == NULL) continue;
            $images[$key] = $value->signedUrl(new \DateTime('1 min'), ['version' => 'v4']);
        }

        return $images;
    }

}