<?php
namespace TurnosAPI\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Http\Response;

class ProfessionalController extends AbstractRestfulController
{
    protected $em;
    protected $sm;

    public function __construct($em, $sm)
    {
        $this->em = $em;
        $this->sm = $sm;
    }

    public function indexAction(){
        $id = $this->params()->fromRoute('id', NULL);
        $data = $this->getRequest()->getPost()->toArray();

        if($this->getRequest()->isPost()){            
            return $this->post($data);
        }
        else
        if($this->getRequest()->isPut()){
            return $this->put($data, $id);
        }
        else
        if($this->getRequest()->isPatch()){
            return $this->patch($data, $id);
        }
        else
        if($this->getRequest()->isDelete()){
            return $this->delete($id);
        }

        return $this->get($id);
    }

    public function getSpecialtiesAction()
    {
        $id = $this->params()->fromRoute('id');
        $professional = $this->em->find('Admin\Entity\Professional', $id);

        return new JsonModel($professional->getSpecialtiesArray());
    }

    public function getDaysByMedicalCenterAction()
    {
        $id = $this->params()->fromRoute('id');
        $medical_center_id = $this->params()->fromRoute('medical_center');

        $config = $this->em->getConfiguration();
        $config->addCustomStringFunction('DATE_FORMAT','DoctrineExtensions\Query\Mysql\DateFormat');

        return new JsonModel($this->em->createQuery('
            SELECT
            c.id, DATE_FORMAT(c.starting_at, \'%Y-%m-%d\') as value
            FROM Admin\Entity\ProfessionalCalendar c
            WHERE c.professional_id = :professional_id AND c.medical_center_id = :medical_center_id
        ')
        ->setParameters([
            'professional_id' => $id,
            'medical_center_id' => $medical_center_id
        ])
        ->getResult());
    }

    public function getTimeByDayAndMedicalCenterAction()
    {
        $id = $this->params()->fromRoute('id');
        $medical_center_id = $this->params()->fromRoute('medical_center');
        $day = $this->params()->fromRoute('day');

        $config = $this->em->getConfiguration();
        $config->addCustomStringFunction('DATE','DoctrineExtensions\Query\Mysql\Date');

        $professional_calendar = $this->em->createQuery('
            SELECT
            c.id, c.shifts_offer
            FROM Admin\Entity\ProfessionalCalendar c
            WHERE c.professional_id = :professional_id AND c.medical_center_id = :medical_center_id AND DATE(c.starting_at) = :starting_at
        ')
        ->setParameters([
            'professional_id' => $id,
            'medical_center_id' => $medical_center_id,
            'starting_at' => (new \DateTime($day))->format('Y-m-d')
        ])
        ->getOneOrNullResult();

        if($professional_calendar == NULL) return new JsonModel();

        $shifts_offer = json_decode($professional_calendar['shifts_offer'], true);
        $array = [];

        foreach($shifts_offer as $shift){
            $array[] = [
                'id' => $shift,
                'value' => $shift
            ];
        }
        return new JsonModel([
            'professional_calendar_id' => $professional_calendar['id'],
            'times' => $array
        ]);
    }

    public function get($id = NULL)
    {
        $queryBuilder = $this->em->createQueryBuilder()
            ->select('p.id', 'p.first_name', 'p.last_name', 'p.dni', 'p.registration', 'p.college', 'p.cuit')
            ->from('Admin\Entity\Professional', 'p')
            ->where('p.is_active = true');

        if ($id !== NULL) {
            $queryBuilder->andWhere('p.id = :id')
                ->setParameter('id', $id);
            $result = $queryBuilder->getQuery()->getOneOrNullResult();
        } else {
            $result = $queryBuilder->getQuery()->getResult();
        }

        return new JsonModel($result);
    }

    public function post($data)
    {
        return $this->disallow();
    }

    public function put($data, $id)
    {
        return $this->disallow();
    }

    public function patch($data, $id)
    {
        return $this->disallow();
    }

    public function delete($id)
    {
        return $this->disallow();
    }

    private function disallow(){
        $response = new Response();
        $response->setStatusCode(Response::STATUS_CODE_405);
        return $response;
    }
}
