<?php
namespace Auth\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Authentication\Storage\Session as SessionStorage;

class AuthenticationServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new AuthenticationService(
            new SessionStorage('Laminas\Auth', 'session', $container->get('Laminas\Session\SessionManager')),
            $container->get('Auth\Service\AuthAdapter')
        );
    }
}

