<?php
namespace Auth;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Auth\Controller\AuthController;
use Auth\Service\AuthManager;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $event)
    {
        $application = $event->getApplication();
        $sm = $application->getServiceManager();
        
        $eventManager = $application->getEventManager();
        $eventManager->attach('dispatch', [$this, 'setLayout']);
        
        $eventManager->getSharedManager()->attach(AbstractActionController::class, MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);

        $viewModel = $event->getViewModel();
        $appPlugin = $sm->get('ControllerPluginManager')->get('Admin\Controller\Plugin\AppPlugin');
        $viewModel->setVariable('web', $appPlugin->getWeb());

        $sessionManager = $sm->get('Laminas\Session\SessionManager');
        $this->forgetInvalidSession($sessionManager);
    }
    
    protected function forgetInvalidSession($sessionManager) 
    {
    	try {
    		$sessionManager->start();
    		return;
    	} catch (\Exception $e) {
    	}

    	// @codeCoverageIgnoreStart
    	session_unset();
    	// @codeCoverageIgnoreEnd
    }
    
    public function setLayout(MvcEvent $event)
    {
        $matches    = $event->getRouteMatch();
        $controller = $matches->getParam('controller');

        if (false === strpos($controller, __NAMESPACE__)) {
            return;
        }

        $viewModel = $event->getViewModel();
        $viewModel->setTemplate('auth/layout');
    }

    public function onDispatch(MvcEvent $event)
    {
        $controller = $event->getTarget();
        $controllerName = $event->getRouteMatch()->getParam('controller', NULL);
        $actionName = $event->getRouteMatch()->getParam('action', NULL);
        
        // Convert dash-style action name to camel-case.
        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));
        
        // Get the instance of AuthManager service.
        $authManager = $event->getApplication()->getServiceManager()->get(AuthManager::class);

        // Execute the access filter on every controller except AuthController
        // (to avoid infinite redirect).
        if ($controllerName != AuthController::class && !$authManager->filterAccess($controllerName, $actionName)) {
            
            // Remember the URL of the page the user tried to access. We will
            // redirect the user to that URL after successful login.
            $uri = $event->getApplication()->getRequest()->getUri();

            // Make the URL relative (remove scheme, user info, host name and port)
            // to avoid redirecting to other domain by a malicious user.
            $uri->setScheme(NULL)->setHost(NULL)->setPort(NULL)->setUserInfo(NULL);
            $redirectUrl = $uri->toString();

            return $controller->redirect()->toRoute('login', [], ['query' => ['redirectUrl' => $redirectUrl]]);
        }
    }
}
