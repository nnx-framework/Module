<?php
/**
 * @link    https://github.com/nnx-framework/module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\Module\PhpUnit\TestData\IntegrationModule\Custom\Service\Module1;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Nnx\ModuleOptions\ModuleConfigKeyProviderInterface;

/**
 * Class Module
 *
 * @package Nnx\Module\PhpUnit\TestData\IntegrationModule\Custom\Service\Module1
 */
class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ModuleConfigKeyProviderInterface
{
    /**
     * Имя секции в конфиги приложения отвечающей за настройки модуля
     *
     * @var string
     */
    const CONFIG_KEY = 'custom_service_module_1';

    /**
     * Имя модуля
     *
     * @var string
     */
    const MODULE_NAME = __NAMESPACE__;

    /**
     * @return string
     */
    public function getModuleConfigKey()
    {
        return static::CONFIG_KEY;
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
     * @inheritdoc
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
} 