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

class ProfessionalCalendarController extends AbstractActionController
{

    protected $em;
    protected $sm;
    protected $config;
    protected $firestore;
    protected $colection;
    protected $route;

    public function __construct($em, $sm){
        $this->em = $em;
        $this->sm = $sm;
        $this->config = $sm->get('config');

        $this->firestore = new FirestoreClient([
            'projectId' => $this->config['firestore_projectId'],
            'keyFilePath' => $this->config['firestore_keyFilePath']
        ]);

        $this->collection = 'professional_calendar';
        $this->route = 'admin/professional_calendar';

        if($this->sm->get('medical_centers')->medical_center_id == NULL){
            $this->sm->get('medical_centers')->medical_center_id = 1;
        }
    }

    public function indexAction()
    {
        return new ViewModel([
            'title' => 'Agenda Profesional',
            'medical_centers' => $this->getMedicalCenters(),
            'selected_medical_center' => $this->em->find('Admin\Entity\MedicalCenter', $this->sm->get('medical_centers')->medical_center_id),
            'professionals' => $this->em->getRepository('Admin\Entity\Professional')->findBy([], ['last_name' => 'ASC']),
            'route' => $this->route
        ]);
    }

    public function getAction()
    {
        $request = $this->getRequest();
        if(!$request->isPost()){
            header('HTTP/1.0 401');
            exit;
        }

        $data = $request->getPost()->toArray();
        $data[] = $this->params()->fromQuery();

        $config = $this->em->getConfiguration();
        $config->addCustomStringFunction('DATE_FORMAT','DoctrineExtensions\Query\Mysql\DateFormat');

        $results = $this->em->createQuery('
            SELECT 
            c.id,
            c.professional_id,
            CONCAT(p.last_name, \' \', p.first_name) as title,
            DATE_FORMAT(c.starting_at, \'%Y-%m-%d %H:%i:%s\') as start,
            DATE_FORMAT(c.ending_at, \'%Y-%m-%d %H:%i:%s\') as end
            FROM Admin\Entity\ProfessionalCalendar c
            JOIN Admin\Entity\Professional p WITH p.id = c.professional_id
            WHERE 
            c.medical_center_id = :medical_center_id AND 
            c.starting_at >= :starting_at AND c.ending_at <= :ending_at
        ')->setParameters([
            'medical_center_id' => $data['medical_center_id'],
            'starting_at' => $data['start'],
            'ending_at' => $data['end'],
        ])->getResult();

        return new JsonModel($results);
    }

    public function addAction(){
        $request = $this->getRequest();
        if(!$request->isPost()){
            header('HTTP/1.0 401');
            exit;
        }

        $data = $request->getPost()->toArray();
        $data['starting_at'] = new \DateTime($data['starting_day_at'] . ' ' . $data['starting_hour_at']);
        $data['ending_at'] = new \DateTime($data['ending_day_at'] . ' ' . $data['ending_hour_at']);

        $diff = $data['ending_at']->diff($data['starting_at']);
        $day_interval = $diff->days;
        $pivot = new \DateTime($data['starting_day_at']);

        for($i = 0; $i <= $day_interval; $i++){

            $entity = new \Admin\Entity\ProfessionalCalendar([
                'medical_center_id' => $data['medical_center_id'],
                'professional_id' => $data['professional_id'],
                'starting_at' => \DateTime::createFromFormat('Y-m-d H:i', $pivot->format('Y-m-d') . ' ' . $data['starting_hour_at']),
                'ending_at' => \DateTime::createFromFormat('Y-m-d H:i', $pivot->format('Y-m-d') . ' ' . $data['ending_hour_at']),
                'interval_time' => $data['interval_time']
            ]);

            $this->em->persist($entity);
            $pivot->modify('+1 day');
        }

        try {
            $this->em->flush();
            
        }
        catch(\Throwable $e){
            return new JsonModel(['success' => false, 'error' => $e->getMessage()]);
        }

        $this->flashMessenger()->addSuccessMessage('Carga exitosa');
        return new JsonModel(['success' => true]);
    }

    public function deleteAction(){
        $request = $this->getRequest();
        if(!$request->isPost()){
            header('HTTP/1.0 401');
            exit;
        }

        $id = $this->params()->fromRoute('id', 0);
        $entity = $this->em->find('Admin\Entity\ProfessionalCalendar', $id);
        if($entity == NULL) return new JsonModel(['success' => false]);

        $this->em->remove($entity);
        $this->em->flush();

        return new JsonModel(['success' => true]);
    }

    public function setMedicalCenterIdAction(){
        $request = $this->getRequest();
        if(!$request->isPost()){
            header('HTTP/1.0 401');
            exit;
        }

        // Validación por rango


        try{
            $data = $request->getPost()->toArray();
            $this->sm->get('medical_centers')->medical_center_id = $data['medical_center_id'];
        }
        catch(\Throwable $e){
            throw new \Exception($e->getMessage());
        }

        header('HTTP/1.0 200');
        exit;
    }

    private function fetchAll($as_array = false){
        return $this->em->createQuery('SELECT i FROM Admin\Entity\ProfessionalCalendar i ORDER BY i.id DESC')->getResult($as_array ? \Doctrine\ORM\Query::HYDRATE_ARRAY : NULL);
    }

    private function getMedicalCenters(){
        return $this->em->getRepository('Admin\Entity\MedicalCenter')->findAll();
    }

    public function getByAction(){
        $request = $this->getRequest();
        /*if(!$request->isPost()){
            header('HTTP/1.0 401');
            exit;
        }
        die;*/

        $data = $request->getPost()->toArray();
        $data = [
            'medical_center_id' => 2,
            'specialty_id' => 1,
            'professional_id' => 3,
        ];

        echo '<pre>' , print_r($data) , '</pre>';
        die;
    }

}