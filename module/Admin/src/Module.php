<?php
namespace Admin;

use Laminas\Mvc\MvcEvent;
use Laminas\Session\SessionManager;
use Laminas\View\HelperPluginManager;
use PHPMailer\PHPMailer\PHPMailer;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $event)
    {
        $app = $event->getParam('application');
        $app->getEventManager()->attach('dispatch', [$this, 'setLayout']);

        $application = $event->getApplication();

        $serviceManager = $application->getServiceManager();
        $sessionManager = $serviceManager->get(SessionManager::class);

        $eventManager = $application->getEventManager();
        $eventManager->attach('dispatch', [$this, 'loadConfiguration'], 2);
        $eventManager->attach('dispatch.error', [$this, 'emailException'], 2);
        $eventManager->attach('render.error', [$this, 'emailException'], 2);
    }

    public function setLayout(MvcEvent $event)
    {
        $matches    = $event->getRouteMatch();
        $controller = $matches->getParam('controller');
        if (false === strpos($controller, __NAMESPACE__)) {
            return;
        }
        $viewModel = $event->getViewModel();
        $viewModel->setTemplate('admin/layout');
    }
    
    public function loadConfiguration(MvcEvent $event)
    {
        $application = $event->getApplication();
        $sm = $application->getServiceManager();
        $sharedManager = $application->getEventManager()->getSharedManager();

        $appPlugin = $sm->get('ControllerPluginManager')->get('Admin\Controller\Plugin\AppPlugin');
        $viewModel = $event->getViewModel();
        $viewModel->setVariable('web', $appPlugin->getWeb());
    }

    public function emailException(MvcEvent $event)
    {
        $this->loadConfiguration($event);

        $vm = $event->getViewModel();
        $vm->setTemplate('layout/error');

        $application = $event->getApplication();
        $config = $application->getConfig();

        if($config['isTest']) return false;

        $exception = $event->getParam('exception');
        if($exception != null){
            $exceptionName = $exception->getMessage();
            $file = $exception->getFile();
            $line = $exception->getLine();
            $stackTrace = $exception->getTraceAsString();
        }
        $errorMessage = $event->getError();
        $controllerName = $event->getController();

        if($errorMessage == 'error-router-no-match') return false;

        // Prepare email message.
        $body = '';
        if(isset($_SERVER['REQUEST_URI'])) {
            $body .= "Request URI: " . $_SERVER['REQUEST_URI'] . "\n\n";
        }
        $body .= "Controller: $controllerName\n";
        $body .= "Error message: $errorMessage\n";
        if ($exception != null) {
            $body .= "Exception: $exceptionName\n";
            $body .= "File: $file\n";
            $body .= "Line: $line\n";
            $body .= "Stack trace:\n\n" . $stackTrace;
            $body .= "<pre>" . print_r($_SESSION, true) . "<pre>";
        }

        $body = str_replace("\n", "<br>", $body);

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        //$mail->SMTPDebug = 1;
        $mail->Host = $config['exceptionHost'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['exceptionEmail'];
        $mail->Password = $config['exceptionEmailPwd'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = $config['exceptionHostPort'];
        $mail->CharSet = 'UTF-8';

        try {
            $mail->setFrom($config['emailErrors'], $config['name']);
            $mail->addAddress($config['exceptionEmailTo']);
            $mail->isHTML(true);
            $mail->Subject = $config['name'] . ' - Error';
            $mail->Body    = $body;
            $mail->AltBody = html_entity_decode($body);
            $mail->send();
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
   }
}