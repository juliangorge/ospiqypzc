<?php
namespace Auth\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Auth\Service\AuthManager;

class AuthManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $get_config = $container->get('Config');

        $config = [];
        $config['acl'] = $get_config['acl'];
        if (isset($get_config['access_filter'])){
            $config['access_filter'] = $get_config['access_filter'];
        }
                        
        return new AuthManager(
            $container->get('Laminas\Authentication\AuthenticationService'),
            $container->get('Laminas\Session\SessionManager'), 
            $config
        );
    }
}
