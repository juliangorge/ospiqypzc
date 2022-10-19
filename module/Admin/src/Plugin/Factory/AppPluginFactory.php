<?php
namespace Admin\Plugin\Factory; 

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Admin\Plugin\AppPlugin;

class AppPluginFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $authManager = $container->get(\Juliangorge\Users\Service\AuthManager::class);
        $userManager = $container->get(\Juliangorge\Users\Service\UserManager::class);
        $config = $container->get('config');

        return new AppPlugin(
            $entityManager,
            $config,
            $authManager,
            $userManager
        );
    }

}