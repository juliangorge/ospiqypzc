<?php
namespace Admin;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\I18n\Translator;
use Laminas\I18n\Translator\Translator as I18nTranslator;
use Laminas\Validator\AbstractValidator;

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $event)
    {
        $application = $event->getApplication();
        $serviceManager = $application->getServiceManager();

        $eventManager = $application->getEventManager();
        $eventManager->attach('dispatch', [$this, 'dispatch'], 2);
        $eventManager->attach('dispatch.error', [$this, 'dispatchError'], 2);
        $eventManager->attach('render.error', [$this, 'dispatchError'], 2);

        $this->translate($eventManager);
    }

    public function loadConfiguration(MvcEvent $event)
    {
        $application = $event->getApplication();
        $sm = $application->getServiceManager();

        $appPlugin = $sm->get('ControllerPluginManager')->get('Admin\Plugin\AppPlugin');
        $viewModel = $event->getViewModel();
        $viewModel->setVariable('web', $appPlugin->getWeb());
    }

    public function dispatch(MvcEvent $event)
    {
        $this->loadConfiguration($event);
        $viewModel = $event->getViewModel();
        $viewModel->setTemplate('layout/admin');
    }

    public function dispatchError(MvcEvent $event)
    {
        $this->loadConfiguration($event);
        $viewModel = $event->getViewModel();
        $viewModel->setTemplate('layout/error');
    }

    public function translate($eventManager)
    {
        $moduleRouteListener = new \Laminas\Mvc\ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    
        $t = new I18nTranslator();
        $t->setLocale('es_ES');
    
        $translator = new Translator($t);
        $translator->addTranslationFile('phpArray', 'vendor/laminas/laminas-i18n-resources/languages/es/Laminas_Validate.php', 'default', 'es_ES');
        AbstractValidator::setDefaultTranslator($translator);
    }

}