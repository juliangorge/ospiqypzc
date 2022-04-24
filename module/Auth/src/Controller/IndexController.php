<?php
namespace Auth\Controller;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Authentication\Result;
use Laminas\Uri\Uri;
use Auth\Entity\User;

class IndexController extends AbstractActionController
{
    private $em;

    private $authManager;

    private $userManager;

    private $config;

    public function __construct($em, $authManager, $userManager, $config)
    {
        $this->em = $em;
        $this->authManager = $authManager;
        $this->userManager = $userManager;
        $this->config = $config;
    }

    public function loginAction()
    {
        if($this->identity()) return $this->redirect()->toRoute('admin');

        $redirectUrl = (string)$this->params()->fromQuery('redirectUrl', '');
        if (strlen($redirectUrl) > 2048) {
            throw new \Exception("Too long redirectUrl argument passed");
        }

        $form = new \Auth\Form\LoginForm();
        $form->get('redirect_url')->setValue($redirectUrl);

        $isLoginError = false;

        if ($this->getRequest()->isPost())
        {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();

                $result = $this->authManager->login($data['email'], $data['password'], $data['remember_me']);
                if ($result->getCode() == Result::SUCCESS) {

                    $redirectUrl = $this->params()->fromPost('redirect_url', '');

                    if (!empty($redirectUrl)) {
                        // Prevent possible redirect attack
                        $uri = new Uri($redirectUrl);
                        if (!$uri->isValid() || $uri->getHost() != NULL)
                            throw new \Exception('Incorrecta redirección URL: ' . $redirectUrl);
                    }

                    if(empty($redirectUrl)) {
                        return $this->redirect()->toRoute('admin');
                    } else {
                        $this->redirect()->toUrl($redirectUrl);
                    }

                } else {
                    $isLoginError = true;
                }

            } else {
                $isLoginError = true;
            }
        }

        return new ViewModel([
            'form' => $form,
            'isLoginError' => $isLoginError,
            'redirectUrl' => $redirectUrl
        ]);
    }

    public function logoutAction()
    {
        if($this->identity()) $this->authManager->logout();
        return $this->redirect()->toRoute('admin');
    }

    public function registerAction(){
        $redirectUrl = (string) $this->params()->fromQuery('redirectUrl', '');
        if (strlen($redirectUrl) > 2048) throw new \Exception('Parámetro inválido');

        $form = new \Auth\Form\RegisterForm($this->em);
        $form->get('redirect_url')->setValue($redirectUrl);
        
        if ($this->getRequest()->isPost()) {
            
            $data = $this->params()->fromPost();
            
            $form->setData($data);
            
            if ($form->isValid()) {
                $data = $form->getData();

                $user = $this->userManager->addUser($data);
                $this->authManager->login($user->getEmail(), $user->getPassword(), 0, 1);

                $redirectUrl = $this->params()->fromPost('redirect_url', '');

                if (!empty($redirectUrl)) {
                    // Prevent possible redirect attack
                    $uri = new Uri($redirectUrl);

                    if (!$uri->isValid() || $uri->getHost() != NULL) 
                        throw new \Exception('Incorrecta redirección URL: ' . $redirectUrl);
                }

                if(empty($redirectUrl)) {
                    return $this->redirect()->toRoute('admin', [], ['query' => ['from' => 'new_user']]);
                } else {
                    $this->redirect()->toUrl($redirectUrl);
                }

            }
        } 
        
        return new ViewModel([
            'form' => $form
        ]);
    }

    public function resetPasswordAction(){
        $form = new \Auth\Form\PasswordResetForm();
        
        if ($this->getRequest()->isPost()) {

            $data = $this->params()->fromPost();

            $form->setData($data);

            if($form->isValid()) {
                $user = $this->em->getRepository(User::class)->findOneByEmail($data['email']);
                
                if ($user != NULL && $user->getStatus() == User::STATUS_ACTIVE) {
                    $this->userManager->generatePasswordResetToken($user);
                    
                    return $this->redirect()->toRoute('users', ['action' => 'message', 'id' => 'sent']);                 
                } else {
                    return $this->redirect()->toRoute('users', ['action' => 'message', 'id' => 'invalid-email']);
                }

            }               
        } 
        
        return new ViewModel([                    
            'form' => $form
        ]);
    }

    public function setPasswordAction(){
        $email = $this->params()->fromQuery('email', NULL);
        $token = $this->params()->fromQuery('token', NULL);
        
        if ($token != NULL && (!is_string($token) || strlen($token) != 32)) {
            throw new \Exception('Token inválido');
        }
        
        if($token === NULL || !$this->userManager->validatePasswordResetToken($email, $token)) {
            return $this->redirect()->toRoute('users', ['action' => 'message', 'id' => 'failed']);
        }
                
        $form = new \Auth\Form\PasswordChangeForm('reset');
        
        if($this->getRequest()->isPost()) {
            
            $data = $this->params()->fromPost();            
            
            $form->setData($data);
            
            if($form->isValid()){
                
                $data = $form->getData();

                return $this->redirect()->toRoute('users', ['action' => 'message', 'id' => ($this->userManager->setNewPasswordByToken($email, $token, $data['new_password']) ? 'set' : 'failed')]);
            }               
        } 
        
        return new ViewModel([
            'form' => $form
        ]);
    }

    public function changePasswordAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        if(!$id < 1){
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $user = $this->em->find(User::class, $id);
        if ($user == NULL) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $form = new \Auth\Form\PasswordChangeForm('change');

        if ($this->getRequest()->isPost()) {
            
            $data = $this->params()->fromPost();
            $form->setData($data);
            
            if($form->isValid()) {
                
                $data = $form->getData();
                
                if (!$this->userManager->changePassword($user, $data)) {
                    $this->flashMessenger()->addErrorMessage('Contraseña actual incorrecta.');
                } else {
                    $this->flashMessenger()->addSuccessMessage('Cambio de contraseña exitoso.');
                }
                
                return $this->redirect()->toRoute('users', ['action' => 'view', 'id' => $user->getId()]);
            }               
        } 
        
        return new ViewModel([
            'user' => $user,
            'form' => $form
        ]);
    }

    public function messageAction() 
    {
        $id = (string) $this->params()->fromRoute('id');
        
        if($id != 'invalid-email' && $id != 'sent' && $id != 'set' && $id != 'failed') {
            throw new \Exception('Mensaje inválido');
        }

        return new ViewModel([
            'id' => $id
        ]);
    }
}
