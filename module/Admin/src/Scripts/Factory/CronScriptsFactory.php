<?php

declare(strict_types=1);

namespace Admin\Scripts\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class CronScriptsFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        $controller = new $requestedName(
            $container->get('doctrine.entitymanager.orm_default'),
            $container->get('config')
        );

        return $controller;

    }

}