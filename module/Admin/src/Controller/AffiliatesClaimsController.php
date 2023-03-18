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

class AffiliatesClaimsController extends AbstractActionController
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

        $this->collection = 'affiliates_claims';
        $this->route = 'admin/affiliates_claims';
    }

    public function indexAction()
    {
        $reclamo = $this->firestore->collection($this->collection)->document('WVDg1c4vfRmK5PemVSRp');
        $docRef = $reclamo->snapshot();
        $claim = new \Admin\Entity\AffiliatesClaims();

        $array = array_merge_recursive($docRef->data(), ['document_id' => 'WVDg1c4vfRmK5PemVSRp']);
        $claim->fromFirebase($array);

        $this->em->persist($claim);
        $this->em->flush();

        $doctrineCollection = new ArrayCollection($this->fetchAll());
        $adapter = new CollectionAdapter($doctrineCollection);
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('p', 1))->setItemCountPerPage(30);

        return new ViewModel([
            'title' => 'Reclamos',
            'results' => $paginator,
            'route' => $this->route,
            'role' => $this->appPlugin()->getUserRole()
        ]);
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->find('Admin\Entity\AffiliatesClaims', $id);
        if($entity == NULL) return $this->redirect()->toRoute($this->route);

        $readonly = $entity->getDateAnswer() == NULL;
        $form = new \Admin\Form\AffiliatesClaims($this->em, NULL, !$readonly);
        $form->bind($entity);

        $affiliate = $this->em->getRepository('Admin\Entity\Affiliates')->findOneBy(['dni' => $entity->getDni()]);
        if($affiliate == NULL){
            $affiliate = $this->em->getRepository('Admin\Entity\AffiliatesFamily')->findOneBy(['dni' => $entity->getDni()]);
            if($affiliate == NULL) return $this->redirect()->toRoute($this->route);
        }

        $form->get('affiliate_fullname')->setValue($affiliate->getFullName());

        $request = $this->getRequest();
        $success = true;
        if($request->isPost()){
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());

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

        return new ViewModel([
            'form' => $form,
            'title' => 'Reclamo',
            'item' => $entity,
            'route' => $this->route
        ]);
    }

    public function viewAction(){
        $id = $this->params()->fromRoute('id', 0);
        if(!$id) return $this->redirect()->toRoute($this->route);

        $entity = $this->em->createQuery('
            SELECT
            i.id,
            i.claim_id,
            i.title,
            i.details,
            i.details_answer,
            i.date_answer,
            i.date_created,
            i.status,
            i.user_id,
            i.dni,
            i.document_id,
            CONCAT(a.first_name, \' \', a.last_name) as full_name,
            CONCAT(u.first_name, \' \', u.last_name) as administrative_name
            FROM Admin\Entity\AffiliatesClaims i 
            INNER JOIN Admin\Entity\Affiliates a WITH a.dni = i.dni
            LEFT JOIN ' . $this->config['authModule']['userEntity'] . ' u WITH u.id = i.user_id
            WHERE i.id = :id
        ')->setParameters(['id' => $id])
        ->getOneOrNullResult();

        if($entity == NULL) return $this->redirect()->toRoute($this->route);

        return new ViewModel([
            'title' => 'Reclamo',
            'item' => $entity,
            'route' => $this->route
        ]);   
    }

    private function fetchAll($as_array = false){
        return $this->em->createQuery('
            SELECT
            i.id,
            i.claim_id,
            i.title,
            i.details,
            i.details_answer,
            i.date_answer,
            i.date_created,
            i.status,
            i.user_id,
            i.dni,
            i.document_id,
            CONCAT(a.first_name, \' \', a.last_name) as full_name
            FROM Admin\Entity\AffiliatesClaims i 
            INNER JOIN Admin\Entity\Affiliates a WITH a.dni = i.dni
            ORDER BY i.id DESC
        ')->getResult($as_array ? \Doctrine\ORM\Query::HYDRATE_ARRAY : NULL);
    }

}