<?php
namespace TurnosAPI;

use Laminas\ApiTools\Provider\ApiToolsProviderInterface;
use Laminas\Http\Response;
use Laminas\Mvc\MvcEvent;

class Module implements ApiToolsProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $event)
    {
        $application = $event->getApplication();
        $serviceManager = $application->getServiceManager();
        $sharedEventManager = $application->getEventManager()->getSharedManager();
        
        // Only apply authentication for requests to the TurnosAPI module
        $sharedEventManager->attach(
            'TurnosAPI\Controller',
            MvcEvent::EVENT_DISPATCH,
            function (MvcEvent $event) use ($serviceManager) {
                if(!$this->checkAuth($event)){
                    $response = $event->getResponse();
                    $response->setStatusCode(401);
                    $response->sendHeaders();
                    exit;
                }
            },
            100
        );
    }

    public function checkAuth(MvcEvent $event)
    {
        $headers = $event->getRequest()->getHeaders();
        $validAccess = $headers->has('Authorization');

        if($validAccess){
            $authHeader = $headers->get('Authorization');
            $validAccess = ($authHeader != false);
        }

        if($validAccess){
            $value = $authHeader->getFieldValue();
            if (substr($value, 0, 6) !== 'Basic ') {
                $validAccess = false;
            }
            else{

                $encodedCredentials = substr($value, 6);
                $decodedCredentials = base64_decode($encodedCredentials);

                try {
                    list($username, $password) = explode(':', $decodedCredentials);
                }
                catch(\Throwable $e){
                    $validAccess = false;
                }
            }
        }

        if($validAccess){
            $htpasswdPath = __DIR__ . '/../../data/users.htpasswd';
            $htpasswd = file_get_contents($htpasswdPath);

            $passwordHash = '';
            $lines = explode("\n", $htpasswd);
            
            foreach ($lines as $line) {
                list($htUser, $htPass) = explode(':', $line);
                if ($htUser === $username) {
                    $passwordHash = trim($htPass);
                    break;
                }
            }

            $validAccess = $password == $passwordHash;
        }

        return $validAccess;
    }

    public function getAutoloaderConfig()
    {
        return [
            'Laminas\ApiTools\Autoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src',
                ],
            ],
        ];
    }
}
