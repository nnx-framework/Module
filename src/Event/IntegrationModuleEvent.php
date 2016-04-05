<?php
/**
 * @link    https://github.com/nnx-framework/module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\Module\Event;

use Zend\EventManager\Event;
use Zend\ModuleManager\ModuleManagerInterface;

/**
 * Class IntegrationModuleEvent
 *
 * @package Nnx\Module\Event
 */
class IntegrationModuleEvent extends Event implements IntegrationModuleEventInterface
{
    /**
     * Менеджер модулей
     *
     * @var ModuleManagerInterface
     */
    protected $moduleManager;

    /**
     * Объект модуля для котрого происходит инциацилзация
     *
     * @var mixed
     */
    protected $module;

    /**
     * @inheritdoc
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
     * @inheritdoc
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

    /**
     * @inheritdoc
     *
     * @return mixed
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Устанавливает объект модуля для котрого происходит инциацилзация
     *
     * @param mixed $module
     *
     * @return $this
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }
}
