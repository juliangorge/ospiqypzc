<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

class IndexController extends AbstractActionController 
{

    private $em;
    private $config;
    private $route;
    private $serviceManager;

    public function __construct($em, $config){
        $this->em = $em;
        $this->config = $config;
        $this->route = 'admin/dashboard';
    }

    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function indexAction()
    {

    }

    public function logsAction()
    {
        return new ViewModel([
            'title' => 'Logs'
        ]);   
    }

    public function miCuentaAction()
    {
        return new ViewModel([
            'user' => $this->em->find($this->config['authModule']['userEntity'], $this->identity()['id'])
        ]);
    }

    public function cambiarContraseñaAction()
    {
        $entity = $this->em->find($this->config['authModule']['userEntity'], $this->identity()['id']);
        if($entity == NULL) return $this->redirect()->toRoute($this->route);

        $form = new \Juliangorge\Users\Form\PasswordChangeForm($this->em);
        $form->bind($entity);

        $request = $this->getRequest();

        $success = true;
        if($request->isPost()){
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $form->setData($post);

            if($form->isValid()){
                try {
                    $userManager = $this->serviceManager->get($this->config['authModule']['userManager']);
                    $userManager->changePassword($entity, $post, false);
                }catch(\Throwable $e){
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                    $success = false;
                }

            }else{
                $this->flashMessenger()->addErrorMessage($form->getMessages());
                $success = false;
            }
            
            if($success){

                $this->flashMessenger()->addSuccessMessage('Carga exitosa');
                return $this->redirect()->toRoute($this->route, ['action' => 'miCuenta']);
            }
        }

        return new ViewModel([
            'form' => $form,
            'title' => 'Cambiar contraseña',
            'route' => $this->route,
            'item' => $entity,
            'actionForm' => '/' . $this->route . '/cambiar-contraseña'
        ]);
    }

    public function notificacionesAction(){
        return new ViewModel([
            'title' => 'Notificaciones',
        ]);
    }

    public function limpiarNotificacionesAction(){
        $entities = $this->em->getRepository('Juliangorge\Notifications\Entity\PanelNotification')
            ->findBy(['user_id' => $this->identity(), 'active' => true]);
        foreach($entities as $e){
            $e->setActive(false);
        }
        $this->em->flush();

        return $this->redirect()->toRoute('home');
    }

    public function getNotificationsAction(){
        if(!$this->getRequest()->isPost()){
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        $data = $this->getRequest()->getPost()->toArray();

        $plugin = $this->plugin(\Admin\Plugin\AppPlugin::class);
        $filterData = $plugin->buildForDataTables($data);

        $data = $this->em->createQuery('
            SELECT ' . $filterData['columns'] . '
            FROM Juliangorge\Notifications\Entity\PanelNotification i
            ' . ($filterData['filter_by'] != '' ? 'WHERE '. $filterData['filter_by'] : '') . '
            ' . ($filterData['order_by'] != '' ? 'ORDER BY ' . $filterData['order_by'] : '') . '
        ')
        ->setParameters($filterData['parameters'])
        ->setFirstResult($filterData['start'])
        ->setMaxResults($filterData['length'])->getResult();

        return new JsonModel([
            'recordsTotal' => $filterData['length'],
            'recordsFiltered' => $this->em->createQuery('SELECT COUNT(i.id) FROM Juliangorge\Notifications\Entity\PanelNotification i')->getSingleScalarResult(),
            'data' => $data
        ]);
    }

    public function getActiveNotificationsAction(){
        return new JsonModel($this->notifications()->getActivesByUserId($this->identity()['id']));
    }

}