<?php
/**
 * @link    https://github.com/nnx-framework/module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\Module\Listener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Nnx\Module\IntegrationModuleInterface;
use Nnx\Module\Event\IntegrationModuleEventInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\Listener\ConfigMergerInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Nnx\ModuleOptions\ModuleConfigKeyProviderInterface;
use Nnx\Module\CommonModuleOptionsInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Class ModuleOptions
 *
 * @package Nnx\Module\Options
 */
class IntegrationModuleListener extends AbstractListenerAggregate implements IntegrationModuleListenerInterface
{

    /**
     * Стек содержащий объекты событий, полученных когда происходит иницилацзия интеграционного модуля.
     * (порядок соответствует, тому в какой очередности происходила инициализация модулей)
     *
     * @var IntegrationModuleEventInterface[]
     */
    protected $stackInitIntegrationModuleEvent = [];

    /**
     * Идендфикаторы подписчиков на SharedManagerEvent
     *
     * @var array
     */
    protected $sharedListeners = [];

    /**
     * Приоритет обработчки отвечающего за конфигурирование модулей сервиса. Для корректной работы
     * интеграционного модуля, это значение должно быть больше, по приоритету чем значение свойства
     * @see \Nnx\Module\Listener\IntegrationModuleListener::$configuringServiceModulesHandlerPriority, но меньше
     * @see \Nnx\Module\IntegrationModuleTrait::$loadModulesPostProxyHandlerPriority
     *
     *
     * @var int
     */
    protected $configuringServiceModulesHandlerPriority = 50;

    /**
     * @inheritdoc
     *
     * @param EventManagerInterface $events - это EventManager сервиса \Zend\ModuleManager\ModuleManagerInterface
     */
    public function attach(EventManagerInterface $events)
    {
        $sharedEventManager = $events->getSharedManager();
        $integrationModuleEventHandler = $sharedEventManager->attach(
            IntegrationModuleInterface::class,
            IntegrationModuleEventInterface::INIT_INTEGRATION_MODULE_EVENT,
            [$this, 'initIntegrationModuleEventHandler']
        );
        $this->sharedListeners[] = [
            'listener' => $integrationModuleEventHandler,
            'id' => IntegrationModuleInterface::class
        ];

        $this->listeners[] = $events->attach(
            ModuleEvent::EVENT_LOAD_MODULES_POST,
            [$this, 'configuringServiceModulesHandler'],
            $this->configuringServiceModulesHandlerPriority
        );
    }

    /**
     * Обработчик события бросаемого, когда все интеграционные модули иницилизированны
     *
     * @param ModuleEvent $e
     */
    public function configuringServiceModulesHandler(ModuleEvent $e)
    {
        $configListener = $e->getConfigListener();

        $stackIntegrationsModule = $this->getStackInitIntegrationModuleEvent();
        /** @var IntegrationModuleEventInterface[] $sortStackIntegrationsModule */
        $sortStackIntegrationsModule = array_reverse($stackIntegrationsModule);


        foreach ($sortStackIntegrationsModule as $event) {
            $integrationModule = $event->getModule();

            if (!$integrationModule instanceof IntegrationModuleInterface) {
                continue;
            }
            $moduleManager = $event->getModuleManager();

            $this->rebuildServiceModulesConfigs($integrationModule, $moduleManager, $configListener);
        }
    }

    /**
     * Возвращает для итеграционного модуля список настроек, которые должны быть применены, ко всем модулям сервиса
     *
     * @param mixed                 $integrationModule
     * @param ConfigMergerInterface $configListener
     *
     * @return array
     */
    public function getCommonModuleOptionsByIntegrationModule($integrationModule, ConfigMergerInterface $configListener)
    {
        $commonModuleOptions = [];
        if (
            (!$integrationModule instanceof CommonModuleOptionsInterface)
            || (!$integrationModule instanceof ModuleConfigKeyProviderInterface)
        ) {
            return $commonModuleOptions;
        }

        $listCommonModuleOptions = $integrationModule->getCommonModuleOptions();
        $integrationModuleConfigKey = $integrationModule->getModuleConfigKey();

        $appConfig = $configListener->getMergedConfig(false);
        if (!is_array($appConfig)) {
            return $commonModuleOptions;
        }

        $integrationModuleConfig = array_key_exists($integrationModuleConfigKey, $appConfig) ? $appConfig[$integrationModuleConfigKey] : [];

        foreach ($listCommonModuleOptions as $key) {
            $commonModuleOptions[$key] = array_key_exists($key, $integrationModuleConfig) ? $integrationModuleConfig[$key] : null;
        }

        return $commonModuleOptions;
    }

    /**
     * Применяет настройки определенные в интеграционном модуле, для модулей сервиса
     *
     * @param IntegrationModuleInterface $integrationModule
     * @param ModuleManagerInterface     $moduleManager
     * @param ConfigMergerInterface      $configListener
     */
    public function rebuildServiceModulesConfigs(
        IntegrationModuleInterface $integrationModule,
        ModuleManagerInterface $moduleManager,
        ConfigMergerInterface $configListener
    ) {
        $serviceModules = $integrationModule->getServiceModules();

        $loadedModules = $moduleManager->getLoadedModules(false);

        $appConfig = $configListener->getMergedConfig(false);

        $commonModuleOptions = $this->getCommonModuleOptionsByIntegrationModule($integrationModule, $configListener);
        foreach ($serviceModules as $moduleName) {
            if (!array_key_exists($moduleName, $loadedModules)) {
                continue;
            }
            $module = $loadedModules[$moduleName];

            if (!$module instanceof ModuleConfigKeyProviderInterface) {
                continue;
            }

            $moduleConfigKey = $module->getModuleConfigKey();

            $moduleConfig = array_key_exists($moduleConfigKey, $appConfig) ? $appConfig[$moduleConfigKey] : [];

            $newModuleConfig = ArrayUtils::merge($moduleConfig, $commonModuleOptions);

            $appConfig[$moduleConfigKey] = $newModuleConfig;
        }

        $configListener->setMergedConfig($appConfig);
    }

    /**
     * {@inheritDoc}
     */
    public function detach(EventManagerInterface $events)
    {
        parent::detach($events);
        $sharedEventManager = $events->getSharedManager();
        foreach ($this->sharedListeners as $index => $item) {
            if ($sharedEventManager->detach($item['id'], $item['listener'])) {
                unset($this->sharedListeners[$index]);
            }
        }
    }

    /**
     * Обработчик события возникающего при инциализации интеграционного модуля
     *
     * @param IntegrationModuleEventInterface $event
     */
    public function initIntegrationModuleEventHandler(IntegrationModuleEventInterface $event)
    {
        $this->addInitIntegrationModuleEventInStack($event);
    }

    /**
     * Добавляет в стек, содержащий объекты событий бросаемых при иницилацзия интеграционного модуля.
     *
     * @param $initIntegrationModuleEvent $stackInitIntegrationModuleEvent
     *
     * @return $this
     */
    public function addInitIntegrationModuleEventInStack(IntegrationModuleEventInterface $initIntegrationModuleEvent)
    {
        $this->stackInitIntegrationModuleEvent[] = $initIntegrationModuleEvent;

        return $this;
    }

    /**
     * Стек содержащий объекты событий, полученных когда происходит иницилацзия интеграционного модуля.
     * (порядок соответствует, тому в какой очередности происходила инициализация модулей)
     *
     * @return IntegrationModuleEventInterface[]
     */
    public function getStackInitIntegrationModuleEvent()
    {
        return $this->stackInitIntegrationModuleEvent;
    }
}
