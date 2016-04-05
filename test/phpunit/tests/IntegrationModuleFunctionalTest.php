<?php
/**
 * @link    https://github.com/nnx-framework/module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\Module\PhpUnit\Test;

use Nnx\Module\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;


/**
 * Class IntegrationModuleFunctionalTest
 *
 * @package Nnx\Module\PhpUnit\Test
 */
class IntegrationModuleFunctionalTest extends AbstractHttpControllerTestCase
{
    /**
     *
     * @return void
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testModifyConfig()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToIntegrationModuleAppConfig()
        );
        $sl = $this->getApplicationServiceLocator();

        $appConfig = $sl->get('Config');

        return ;
    }
}
