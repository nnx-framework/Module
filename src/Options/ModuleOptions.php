<?php
/**
 * @link    https://github.com/nnx-framework/module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\Module\Options;

use Zend\Stdlib\AbstractOptions;
use Nnx\ModuleOptions\ModuleOptionsInterface;
use Nnx\Module\Options\ModuleOptionsInterface as CurrentModuleOptionsInterface;


/**
 * Class ModuleOptions
 *
 * @package Nnx\Module\Options
 */
class ModuleOptions extends AbstractOptions implements ModuleOptionsInterface, CurrentModuleOptionsInterface
{
}
