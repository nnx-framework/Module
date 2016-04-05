<?php
/**
 * @link    https://github.com/nnx-framework/module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\Module\PhpUnit\TestData\IntegrationModule\Custom\Service\Service;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\EventManager\EventManagerAwareTrait;
use Nnx\ModuleOptions\ModuleConfigKeyProviderInterface;
use Nnx\Module\IntegrationModuleTrait;
use Nnx\Module\IntegrationModuleInterface;
use Nnx\Module\PhpUnit\TestData\IntegrationModule\Custom\Service as CustomService;
use Nnx\Module\CommonModuleOptionsInterface;
use Nnx\Module\PhpUnit\TestData\IntegrationModule\Service\Service\Module as Service;


/**
 * Class Module
 *
 * @package Nnx\Module\PhpUnit\TestData\IntegrationModule\Custom\Service\Service
 */
class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ModuleConfigKeyProviderInterface,
    IntegrationModuleInterface,
    CommonModuleOptionsInterface
{
    use IntegrationModuleTrait, EventManagerAwareTrait;

    /**
     * Имя секции в конфиги приложения отвечающей за настройки модуля
     *
     * @var string
     */
    const CONFIG_KEY = 'custom_service_service';

    /**
     * Имя модуля
     *
     * @var string
     */
    const MODULE_NAME = __NAMESPACE__;

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getCommonModuleOptions()
    {
        return [
            'test_token'
        ];
    }

    /**
     * @return string
     */
    public function getModuleConfigKey()
    {
        return static::CONFIG_KEY;
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getServiceModules()
    {
        return [
            Service::MODULE_NAME,
            CustomService\Module1\Module::MODULE_NAME,
            CustomService\Module2\Module::MODULE_NAME,
            CustomService\Module3\Module::MODULE_NAME,
        ];
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