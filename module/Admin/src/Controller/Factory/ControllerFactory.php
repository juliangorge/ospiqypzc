<?php
namespace Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        $controller = new $requestedName(
            $container->get('doctrine.entitymanager.orm_default'),
            $container->get('config')
        );

        if(method_exists($controller, 'setServiceManager')){
            $controller->setServiceManager($container);
        }

        return $controller;

    }

}