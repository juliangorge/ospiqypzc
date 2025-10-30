<?php

namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

class MedicalShiftsController extends AbstractActionController
{

    protected $em;
    protected $sm;
    protected $config;
    protected $route;
    protected $api_credentials;

    public function __construct($em, $sm)
    {
        $this->em = $em;
        $this->sm = $sm;
        $this->config = $sm->get('config');
        $this->route = 'admin/medical_shifts';

        $this->api_credentials = [
            'username' => $this->config['shift_api_credentials']['username'],
            'password' => $this->config['shift_api_credentials']['password'],
        ];
    }

    public function indexAction()
    {
        return new ViewModel([
            'title' => 'Turnos Médicos',
            'route' => $this->route
        ]);
    }

    public function addAction()
    {
        $request = $this->getRequest();
        $form = new \Admin\Form\MedicalShift($this->em);

        $formErrors = NULL;
        $success = false;

        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();
                $data['user_id'] = $this->identity()['id'];
                $professional_calendar = $this->em->createQuery('
                    SELECT a
                    FROM Admin\Entity\ProfessionalCalendar a
                    WHERE
                    a.medical_center_id = :medical_center_id AND
                    a.professional_id = :professional_id AND
                    DATE(a.starting_at) = :starting_at
                ')
                    ->setParameters([
                        'medical_center_id' => $data['medical_center_id'],
                        'professional_id' => $data['professional_id'],
                        'starting_at' => $data['day']
                    ])
                    ->getOneOrNullResult();
                $data['professional_calendar_id'] = $professional_calendar->getId();

                $shift = new \Admin\Entity\MedicalShift($data);
                $this->em->persist($shift);
                $professional_calendar->removeShiftOffer($data['time']);
                $this->em->flush();
                $success = true;
            } else {
                $formErrors = $form->getMessages();
                $this->layout()->addErrorMessage = $form->getMessages();
            }

            if ($success) return $this->redirect()->toRoute('admin/medical_shifts');
        }

        return new ViewModel([
            'form' => $form,
            'formErrors' => $formErrors,
            'api_credentials' => $this->api_credentials
        ]);
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id', 0);

        $entity = $this->em->find('Admin\Entity\MedicalShift', $id);
        if ($entity == NULL) return $this->redirect()->toRoute('admin/medical_shifts');

        $professional_calendar = $this->em->find('Admin\Entity\ProfessionalCalendar', $entity->getProfessionalCalendarId());

        $request = $this->getRequest();
        $form = new \Admin\Form\MedicalShift($this->em, true);
        $form->bind($entity);

        $formErrors = NULL;
        $success = false;

        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            #echo '<pre>' , print_r($data) , '</pre>';
            #die;
            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();

                echo '<pre>', print_r($data), '</pre>';
                die;
                $data['user_id'] = $this->identity()['id'];
                $professional_calendar = $this->em->createQuery('
                    SELECT a
                    FROM Admin\Entity\ProfessionalCalendar a
                    WHERE
                    a.medical_center_id = :medical_center_id AND
                    a.professional_id = :professional_id AND
                    DATE(a.starting_at) = :starting_at
                ')
                    ->setParameters([
                        'medical_center_id' => $data['medical_center_id'],
                        'professional_id' => $data['professional_id'],
                        'starting_at' => $data['day']
                    ])
                    ->getOneOrNullResult();
                $data['professional_calendar_id'] = $professional_calendar->getId();

                $shift->exchangeArray($data);
                $this->em->persist($shift);
                $professional_calendar->removeShiftOffer($data['time']);
                $this->em->flush();
                $success = true;
            } else {
                $formErrors = $form->getMessages();
                $this->layout()->addErrorMessage = $form->getMessages();
            }

            if ($success) return $this->redirect()->toRoute('admin/medical_shifts');
        }

        return new ViewModel([
            'form' => $form,
            'formErrors' => $formErrors,
            'api_credentials' => $this->api_credentials,
            'entity' => $entity,
            'professional_calendar' => $professional_calendar
        ]);
    }

    public function getAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            header('HTTP/1.0 401');
            exit;
        }

        $data = $request->getPost()->toArray();

        $filter = $this->appPlugin()->buildForDataTables($data);

        $config = $this->em->getConfiguration();
        $config->addCustomStringFunction('DATE_FORMAT', 'DoctrineExtensions\Query\Mysql\DateFormat');

        $old_alias = [
            'i.specialty_name',
            'i.shift_datetime',
            'i.professional_first_name',
            'i.professional_last_name',
            'i.affiliate_first_name',
            'i.affiliate_last_name',
            'i.family_first_name',
            'i.family_last_name',
            'i.medical_center_name',
            'i.status'
        ];

        $new_alias = [
            'z.name',
            's.shift_datetime',
            'p.first_name',
            'p.last_name',
            'a.first_name',
            'a.last_name',
            'f.first_name',
            'f.last_name',
            'm.name',
            's.status'
        ];

        $filter['filter_by'] = str_replace($old_alias, $new_alias, $filter['filter_by']);
        $filter['order_by'] = str_replace($old_alias, $new_alias, $filter['order_by']);

        $data = $this->em->createQuery('
            SELECT 
            s.id,
            CONCAT(UPPER(p.last_name), \', \', p.first_name) as professional_name,
            z.name as specialty_name,
            CONCAT(UPPER(a.last_name), \', \', a.first_name) as affiliate_full_name,
            CONCAT(UPPER(f.last_name), \', \', f.first_name) as family_full_name,
            s.status,
            DATE_FORMAT(s.shift_datetime, \'%d/%m/%y %H:%i\') as shift_datetime,
            m.name as medical_center_name
            FROM Admin\Entity\MedicalShift s
            JOIN Admin\Entity\ProfessionalCalendar c WITH c.id = s.professional_calendar_id
            JOIN Admin\Entity\Professional p WITH p.id = c.professional_id
            JOIN Admin\Entity\Specialty z WITH z.id = s.specialty_id
            JOIN Admin\Entity\MedicalCenter m WITH m.id = c.medical_center_id

            LEFT JOIN Admin\Entity\Affiliates a WITH a.dni = s.dni
            LEFT JOIN Admin\Entity\Relatives f WITH f.dni = s.dni

            ' . ($filter['filter_by'] != '' ? 'WHERE ' . $filter['filter_by'] : '') . '
            ' . ($filter['order_by'] != '' ? 'ORDER BY ' . $filter['order_by'] : '') . '
        ')
            ->setParameters($filter['parameters'])
            ->setFirstResult($filter['start'])
            ->setMaxResults($filter['length'])->getResult();

        return new JsonModel([
            'recordsTotal' => $filter['length'],
            'recordsFiltered' => $this->em->createQuery('SELECT COUNT(i.id) FROM Admin\Entity\MedicalShift i')->getSingleScalarResult(),
            'data' => $data
        ]);
    }

    public function cancelAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost()->toArray();

            $entity = $this->em->find('Admin\Entity\MedicalShift', $data['medical_shift_id']);
            if ($entity == NULL) {
                $this->flashMessenger()->addErrorMessage('Turno inexistente');
                return $this->redirect()->toRoute('admin/medical_shifts');
            }

            $entity->setStatus(1);
            $this->em->flush();
        }

        $this->flashMessenger()->addSuccessMessage('Turno anulado con éxito');
        return $this->redirect()->toRoute('admin/medical_shifts');
    }
}
