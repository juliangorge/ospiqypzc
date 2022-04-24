<?php
namespace Auth\Service\Factory;

use Interop\Container\ContainerInterface;
use Auth\Service\AuthAdapter;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AuthAdapterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        return new AuthAdapter($container->get('doctrine.entitymanager.orm_default'));
    }
}
