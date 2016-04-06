<?php
/**
 * @link    https://github.com/nnx-framework/module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\Module;

use Nnx\Module\Event\IntegrationModuleEvent;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\ModuleManager\ModuleEvent;
use Nnx\Module\Event\IntegrationModuleEventInterface;
use ReflectionClass;

/**
 * Class IntegrationModuleTrait
 *
 * @package Nnx\Module\Options
 */
trait IntegrationModuleTrait
{
    /**
     * @see \Zend\EventManager\EventManagerAwareTrait::setEventManager
     *
     * @var array
     */
    protected $eventIdentifier = [
        IntegrationModuleInterface::class
    ];

    /**
     * Протип объекта, испольуземый для создания события бросаемого при инициализации интеграционного модуля
     *
     * @var IntegrationModuleEventInterface
     */
    protected $prototypeIntegrationModuleEvent;

    /**
     * Имя класса, который должен имплементировать \Nnx\Module\Event\IntegrationModuleEventInterface. Данный класс
     * используется для создания объекта прототипа события (@see \Nnx\Module\IntegrationModuleTrait::$prototypeIntegrationModuleEvent)
     *
     * @var string
     */
    protected $prototypeIntegrationModuleEventClassName = IntegrationModuleEvent::class;

    /**
     * Менеджер модулей
     *
     * @var ModuleManagerInterface
     */
    protected $moduleManager;

    /**
     * Приоритет обработчки отвечающего за проксирование события пост загрузки всех модулей. Для корректной работы
     * интеграционного модуля, это значение должно быть больше, по приоритету чем значение свойства 
     * @see \Nnx\Module\Listener\IntegrationModuleListener::$configuringServiceModulesHandlerPriority
     *
     *
     * при подписке на onLoadModulesPost в ServiceListener
     * (@see \Zend\ModuleManager\Listener\ServiceListener::attach) и меньше чем 
     *
     *
     *
     * @var int
     */
    protected $loadModulesPostProxyHandlerPriority = 100;

    /**
     * @return EventManagerInterface
     */
    abstract public function getEventManager();

    /**
     * Initialize workflow
     *
     * @param  ModuleManagerInterface $manager
     * @return void
     */
    public function init(ModuleManagerInterface $manager)
    {
        $this->initIntegrationModule($manager);
    }

    /**
     * Инициализация интеграционного модуля
     *
     * @param ModuleManagerInterface $manager
     */
    public function initIntegrationModule(ModuleManagerInterface $manager)
    {
        $this->setModuleManager($manager);
        $this->preInitIntegrationModule();

        $manager->getEventManager()->attach(
            ModuleEvent::EVENT_LOAD_MODULES_POST,
            [$this, 'onLoadModulesPostProxyHandler'],
            $this->loadModulesPostProxyHandlerPriority
        );
    }

    /**
     * Метод вызывается перед стандартным механизмом инициализаци. Используется для перегрузки в конкретном модуле.
     *
     * @return void
     */
    protected function preInitIntegrationModule()
    {
    }

    /**
     * Обработчик события возникающего после загрузки модулей
     *
     * @throws Exception\ErrorCreateIntegrationModuleEventException
     *
     * @throws Exception\InvalidModuleManagerException
     */
    public function onLoadModulesPostProxyHandler()
    {
        $event = clone $this->getPrototypeIntegrationModuleEvent();
        $event->setName(IntegrationModuleEventInterface::INIT_INTEGRATION_MODULE_EVENT);
        $event->setTarget($this);

        $moduleManager = $this->getModuleManager();
        $event->setModuleManager($moduleManager);

        $event->setModule($this);

        $this->getEventManager()->trigger($event);
    }

    /**
     * Возвращает протип объекта, испольуземый для создания события бросаемого при инициализации интеграционного модуля
     *
     * @return IntegrationModuleEventInterface
     *
     * @throws Exception\ErrorCreateIntegrationModuleEventException
     */
    public function getPrototypeIntegrationModuleEvent()
    {
        if ($this->prototypeIntegrationModuleEvent instanceof IntegrationModuleEventInterface) {
            return $this->prototypeIntegrationModuleEvent;
        }

        $eventClassName = $this->getPrototypeIntegrationModuleEventClassName();

        $r = new ReflectionClass($eventClassName);
        $event = $r->newInstance();

        if (!$event instanceof IntegrationModuleEventInterface) {
            $errMsg = sprintf('Integration module event not implement %s', IntegrationModuleEventInterface::class);
            throw new Exception\ErrorCreateIntegrationModuleEventException($errMsg);
        }

        $this->prototypeIntegrationModuleEvent = $event;

        return $this->prototypeIntegrationModuleEvent;
    }

    /**
     * Устанавливает протип объекта, испольуземый для создания события бросаемого при инициализации интеграционного модуля
     *
     * @param IntegrationModuleEventInterface $prototypeIntegrationModuleEvent
     *
     * @return $this
     */
    public function setPrototypeIntegrationModuleEvent(IntegrationModuleEventInterface $prototypeIntegrationModuleEvent)
    {
        $this->prototypeIntegrationModuleEvent = $prototypeIntegrationModuleEvent;

        return $this;
    }

    /**
     * Возвращает имя класса, который должен имплементировать \Nnx\Module\Event\IntegrationModuleEventInterface. Данный класс
     * используется для создания объекта прототипа события (@see \Nnx\Module\IntegrationModuleTrait::$prototypeIntegrationModuleEvent)
     *
     * @return string
     */
    public function getPrototypeIntegrationModuleEventClassName()
    {
        return $this->prototypeIntegrationModuleEventClassName;
    }

    /**
     * Устанавливает имя класса, который должен имплементировать \Nnx\Module\Event\IntegrationModuleEventInterface. Данный класс
     * используется для создания объекта прототипа события (@see \Nnx\Module\IntegrationModuleTrait::$prototypeIntegrationModuleEvent)
     *
     * @param string $prototypeIntegrationModuleEventClassName
     *
     * @return $this
     */
    public function setPrototypeIntegrationModuleEventClassName($prototypeIntegrationModuleEventClassName)
    {
        $this->prototypeIntegrationModuleEventClassName = $prototypeIntegrationModuleEventClassName;

        return $this;
    }

    /**
     * Возвращает менеджер модулей
     *
     * @return ModuleManagerInterface
     *
     * @throws Exception\InvalidModuleManagerException
     */
    public function getModuleManager()
    {
        if (!$this->moduleManager instanceof ModuleManagerInterface) {
            $errMsg = 'Module manager not installed';
            throw new Exception\InvalidModuleManagerException($errMsg);
        }
        return $this->moduleManager;
    }

    /**
     * Устанавливает менеджер модулей
     *
     * @param ModuleManagerInterface $moduleManager
     *
     * @return $this
     */
    public function setModuleManager(ModuleManagerInterface $moduleManager)
    {
        $this->moduleManager = $moduleManager;

        return $this;
    }
}
