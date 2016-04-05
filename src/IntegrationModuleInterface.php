<?php
/**
 * @link    https://github.com/nnx-framework/module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\Module;

use Zend\ModuleManager\Feature\InitProviderInterface;

/**
 * Interface IntegrationModuleInterface
 *
 * @package Nnx\Module\Options
 */
interface IntegrationModuleInterface extends InitProviderInterface
{
    /**
     * Возвращает список модулей, принадлежащих данному сервису
     *
     * @return array
     */
    public function getServiceModules();
}
