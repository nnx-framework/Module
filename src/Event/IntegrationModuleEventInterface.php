<?php
/**
 * @link    https://github.com/nnx-framework/module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\Module\Event;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\ModuleManagerInterface;

/**
 * Interface IntegrationModuleEventInterface
 *
 * @package Nnx\Module\Event
 */
interface IntegrationModuleEventInterface extends EventInterface
{
    /**
     * Имя события бросаемого при инициализации интеграционного модуля
     *
     * @var string
     */
    const INIT_INTEGRATION_MODULE_EVENT = 'init.integrationModule';


    /**
     * Возвращает менеджер модулей
     *
     * @return ModuleManagerInterface
     *
     */
    public function getModuleManager();

    /**
     * Устанавливает менеджер модулей
     *
     * @param ModuleManagerInterface $moduleManager
     *
     * @return $this
     */
    public function setModuleManager(ModuleManagerInterface $moduleManager);

    /**
     * Возвращает объект модуля для котрого происходит инциацилзация
     *
     * @return mixed
     */
    public function getModule();

    /**
     * @inheritdoc
     *
     * @param mixed $module
     *
     * @return $this
     */
    public function setModule($module);
}
