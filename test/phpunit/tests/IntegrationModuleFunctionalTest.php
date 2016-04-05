<?php
/**
 * @link    https://github.com/nnx-framework/module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\Module\PhpUnit\Test;

use Nnx\Module\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\Module\PhpUnit\TestData\IntegrationModule\Service;
use Nnx\Module\PhpUnit\TestData\IntegrationModule\Custom\Service as CustomService;

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

        $configModuleKeys = [
            Service\Module1\Module::CONFIG_KEY,
            Service\Module2\Module::CONFIG_KEY,
            Service\Module3\Module::CONFIG_KEY,
            Service\Service\Module::CONFIG_KEY,

            CustomService\Module1\Module::CONFIG_KEY,
            CustomService\Module2\Module::CONFIG_KEY,
            CustomService\Module3\Module::CONFIG_KEY,
            CustomService\Service\Module::CONFIG_KEY,
        ];

        foreach ($configModuleKeys as $configModuleKey) {
            static::assertEquals('custom_service_test_token', $appConfig[$configModuleKey]['test_token']);
        }
    }
}
