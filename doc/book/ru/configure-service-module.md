# Конфигурирование модулей сервиса

## Быстрый старт

Приведенные ниже примеры, демонстрируют, как настроить интеграционный модуль, а также модули, образующие сервис, таким
образом. Что бы настройки интеграционного модуля, автоматически были применены, к аналогичным настройкам модуля сервиса.

Подключить модуль Nnx\Module, в конфигурационном файле приложения. **Важно: модуль Nnx\Module должен быть подключен до
модулей сервисов**.

Пример (файл application.config.php):

```php

use Nnx\Module\PhpUnit\TestData\IntegrationModule\Service;
use Nnx\Module\PhpUnit\TestData\IntegrationModule\Custom\Service as CustomService;

return [
    'modules'                 => [
        'Nnx\\Module',

        Service\Module1\Module::MODULE_NAME,
        Service\Module2\Module::MODULE_NAME,
        Service\Module3\Module::MODULE_NAME,
        Service\Service\Module::MODULE_NAME,

        CustomService\Module1\Module::MODULE_NAME,
        CustomService\Module2\Module::MODULE_NAME,
        CustomService\Module3\Module::MODULE_NAME,
        CustomService\Service\Module::MODULE_NAME,

    ],
    'module_listener_options' => [
        'config_glob_paths' => [
            __DIR__ . '/config/autoload/{{,*.}global,{,*.}local}.php',
        ],
    ]
];

```

Для интеграционного модуля добавить интерфейсы:

- \Nnx\Module\IntegrationModuleInterface
    - метод getServiceModules - возвращает массив имен модулей сервиса.
- \Nnx\Module\CommonModuleOptionsInterface
    - метод getCommonModuleOptions - возвращает список общих настроек модулей сервиса (массив ключей конфигурации),
     которые будут установлены из интеграционного модуля. Всем модулям сервиса, в конфиги, будут установлены данные 
     по этими ключами из конфигурационного массива интеграционного модуля.

Для **интеграционного модуля добавить трейт: \Nnx\Module\IntegrationModuleTrait**

Пример интеграционного модуля:

```php

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
```

Убедиться что каждый модуль сервиса, реализует интерфейс \Nnx\ModuleOptions\ModuleConfigKeyProviderInterface.
Метод getModuleConfigKey данного интерфейса, должен возвращать ключ, по которому из конфига приложения можно получить,
настройки для модуля.

Пример файла модуля, входящего в сервис:

```php

/**
 * @link    https://github.com/nnx-framework/module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\Module\PhpUnit\TestData\IntegrationModule\Custom\Service\Module3;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Nnx\ModuleOptions\ModuleConfigKeyProviderInterface;

/**
 * Class Module
 *
 * @package Nnx\Module\PhpUnit\TestData\IntegrationModule\Custom\Service\Module3
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
    const CONFIG_KEY = 'custom_service_module_3';

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

```

