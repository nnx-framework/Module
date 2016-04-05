<?php
/**
 * @link    https://github.com/nnx-framework/module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\Module;


use Nnx\Module\Listener\IntegrationModuleListener;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\Module\Listener\IntegrationModuleListenerInterface;

/**
 * Class Module
 *
 * @package Nnx\ModuleOptions
 */
class Module implements
    BootstrapListenerInterface,
    AutoloaderProviderInterface,
    InitProviderInterface,
    ConfigProviderInterface
{
    /**
     * Имя секции в конфиги приложения отвечающей за настройки модуля
     *
     * @var string
     */
    const CONFIG_KEY = 'nnx_module';

    /**
     * @param ModuleManagerInterface $manager
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws Exception\InvalidIntegrationModuleListenerException
     */
    public function init(ModuleManagerInterface $manager)
    {
        $integrationModuleListener = null;

        if ($manager instanceof ModuleManager) {
            $event =  $manager->getEvent();
            if ($event instanceof EventInterface) {
                $sl = $event->getParam('ServiceManager');
                if ($sl instanceof ServiceLocatorInterface && $sl->has(IntegrationModuleListenerInterface::class)) {
                    $integrationModuleListener = $sl->get(IntegrationModuleListenerInterface::class);
                }
            }
        }

        if (null === $integrationModuleListener) {
            $integrationModuleListener = new IntegrationModuleListener();
        }

        if (!$integrationModuleListener instanceof IntegrationModuleListenerInterface) {
            $errMsg = sprintf('Integration module listener not implement %s', IntegrationModuleListenerInterface::class);
            throw new Exception\InvalidIntegrationModuleListenerException($errMsg);
        }

        $moduleEventManager = $manager->getEventManager();
        $integrationModuleListener->attach($moduleEventManager);

    }

    /**
     * @inheritdoc
     *
     * @param EventInterface $e
     *
     * @return array|void
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var MvcEvent $e */
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

    }


    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/',
                ),
            ),
        );
    }


    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

} 