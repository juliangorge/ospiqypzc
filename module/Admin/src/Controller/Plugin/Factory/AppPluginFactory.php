<?php
namespace Admin\Controller\Plugin\Factory; 

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Auth\Service\AuthManager;
use Auth\Service\UserManager;
use Admin\Controller\Plugin\AppPlugin;

class AppPluginFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $authManager = $container->get(AuthManager::class);
        $userManager = $container->get(UserManager::class);
        $config = $container->get('config');

        return new AppPlugin($entityManager, $config, $authManager, $userManager);
    }
}