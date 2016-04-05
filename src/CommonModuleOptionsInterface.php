<?php
/**
 * @link    https://github.com/nnx-framework/module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\Module;

/**
 * Interface CommonModuleOptionsInterface
 *
 * @package Nnx\Module
 */
interface CommonModuleOptionsInterface
{
    /**
     * Возвращает список опций интеграционного модуля, которые явлюятся общими, для всех модулей сервиса
     *
     * @return array
     */
    public function getCommonModuleOptions();
}
