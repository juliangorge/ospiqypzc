<?php
namespace Auth\Service;

use Laminas\Authentication\Result;

class AuthManager
{
    private $authService;

    private $sessionManager;

    private $config;
    
    public function __construct($authService, $sessionManager, $config) 
    {
        $this->authService = $authService;
        $this->sessionManager = $sessionManager;
        $this->config = $config;
    }

    public function login($email, $password, $rememberMe, $oAuth = false)
    {   
        if ($this->authService->getIdentity() != NULL)
            return $this->redirect()->toRoute('admin');

        // Authenticate with login/password.
        $authAdapter = $this->authService->getAdapter();
        $authAdapter->setEmail($email);
        $authAdapter->setPassword($password);
        $authAdapter->setOAuth($oAuth);
        $result = $this->authService->authenticate();

        if ($result->getCode() == Result::SUCCESS && $rememberMe) {
            $this->sessionManager->rememberMe(60*60*24*30);
        }

        return $result;
    }

    public function getIdentity(){
        return $this->authService->getIdentity();
    }

    public function getSessionId(){
        return $this->sessionManager->getId();
    }

    public function logout()
    {
        if ($this->authService->getIdentity() == NULL) {
            return $this->redirect()->toRoute('admin');
        }
        
        $this->authService->clearIdentity();
    }

    public function filterAccess($controllerName, $actionName)
    {
        $mode = isset($this->config['access_filter']['options']['mode']) ? $this->config['access_filter']['options']['mode'] : 'restrictive';
        if ($mode != 'restrictive' && $mode != 'permissive')
            throw new \Exception('Modo de filtro de acceso inválido (determine un modo restrictive/permissive)');

        if (isset($this->config['access_filter']['controllers'][$controllerName])) {
            $items = $this->config['access_filter']['controllers'][$controllerName];
            foreach ($items as $item) {
                $actionList = $item['actions'];
                $allow = $item['allow'];
                if (is_array($actionList) && in_array($actionName, $actionList) ||
                    $actionList == '*') {
                    if ($allow == '*')
                        return true;
                    else if ($allow == '@' && $this->authService->hasIdentity()) {
                        return true;
                    } else {                    
                        return false;
                    }
                }
            }            
        }
        
        if ($mode == 'restrictive' && !$this->authService->hasIdentity()){
            return false;
        }

        $acl = new \Auth\Acl\Acl($this->config);
        
        if(!$acl->hasResource($controllerName)) throw new \Exception('Resource ' . $controllerName . ' not defined');
        
        return $acl->isAllowed($this->authService->getIdentity()['rank_level'], $controllerName, $actionName);
    }
}