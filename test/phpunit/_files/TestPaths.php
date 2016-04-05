<?php
/**
 * @link    https://github.com/nnx-framework/module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\Module\PhpUnit\TestData;

/**
 * Class TestPaths
 *
 * @package Nnx\Module\PhpUnit\TestData
 */
class TestPaths
{

    /**
     * Путь до директории модуля
     *
     * @return string
     */
    public static function getPathToModule()
    {
        return __DIR__ . '/../../../';
    }

    /**
     * Путь до конфига приложения по умолчанию
     *
     * @return string
     */
    public static function getPathToDefaultAppConfig()
    {
        return  __DIR__ . '/../_files/DefaultApp/application.config.php';
    }

    /**
     * Путь до конфига приложения используемого, для проверки работы объеденения конфигов
     *
     * @return string
     */
    public static function getPathToIntegrationModuleAppConfig()
    {
        return  __DIR__ . '/../_files/IntegrationModule/config/application.config.php';
    }

    /**
     * Путь до директории тестового приложения, отвечающего за проеверку функционала интеграционного модуля, в которой
     * находится модули тестового сервиса
     *
     * @return string
     */
    public static function getPathToIntegrationModuleTestServiceDir()
    {
        return  __DIR__ . '/../_files/IntegrationModule/vendor/service/';
    }


    /**
     * Путь до директории тестового приложения, отвечающего за проеверку функционала интеграционного модуля, в которой
     * находится модули тестового сервиса под конкетного заказчика
     *
     * @return string
     */
    public static function getPathToIntegrationModuleTestCustomServiceDir()
    {
        return  __DIR__ . '/../_files/IntegrationModule/vendor/custom-service/';
    }
}
