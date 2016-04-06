<?php
/**
 * @link    https://github.com/nnx-framework/module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\Module\PhpUnit\Test;

use PHPUnit_Framework_TestCase;
use Nnx\Module\IntegrationModuleTrait;
use Nnx\Module\Event\IntegrationModuleEventInterface;


/**
 * Class IntegrationModuleTraitTest
 *
 * @package Nnx\Module\PhpUnit\Test
 */
class IntegrationModuleTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * Проверка что работает кеширование при создание объекта прототипа события
     *
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Nnx\Module\Exception\ErrorCreateIntegrationModuleEventException
     * @throws \PHPUnit_Framework_AssertionFailedError
     */
    public function testCacheGetPrototypeIntegrationModuleEvent()
    {
        /** @var IntegrationModuleTrait $traitMock */
        $traitMock = $this->getMockForTrait(IntegrationModuleTrait::class);

        $expectedEvent = $traitMock->getPrototypeIntegrationModuleEvent();
        $actualEvent =  $traitMock->getPrototypeIntegrationModuleEvent();

        $result = $expectedEvent === $actualEvent;
        static::assertTrue($result);
    }

    /**
     * Проверка ситуации когда происходит попытка создать объект события, используя некорректный класс
     *
     * @expectedException \Nnx\Module\Exception\ErrorCreateIntegrationModuleEventException
     * @expectedExceptionMessage Integration module event not implement Nnx\Module\Event\IntegrationModuleEventInterface
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Nnx\Module\Exception\ErrorCreateIntegrationModuleEventException
     * @throws \PHPUnit_Framework_AssertionFailedError
     */
    public function testInvalidClassGetPrototypeIntegrationModuleEvent()
    {
        /** @var IntegrationModuleTrait $traitMock */
        $traitMock = $this->getMockForTrait(IntegrationModuleTrait::class);

        $traitMock->setPrototypeIntegrationModuleEventClassName(\stdClass::class);

        $traitMock->getPrototypeIntegrationModuleEvent();
    }

    /**
     * Проверка ситуации когда происходит попытка получить менеджер модулей, не установленный при инициализации
     *
     * @expectedException \Nnx\Module\Exception\InvalidModuleManagerException
     * @expectedExceptionMessage Module manager not installed
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Nnx\Module\Exception\InvalidModuleManagerException
     */
    public function testGetModuleManagerInvalid()
    {
        /** @var IntegrationModuleTrait $traitMock */
        $traitMock = $this->getMockForTrait(IntegrationModuleTrait::class);

        $traitMock->getModuleManager();
    }


    /**
     * Проверка ситуации когда происходит попытка получить менеджер модулей, не установленный при инициализации
     *
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Nnx\Module\Exception\ErrorCreateIntegrationModuleEventException
     * @throws \PHPUnit_Framework_AssertionFailedError
     */
    public function testGetterSetterPrototypeIntegrationModuleEvent()
    {
        /** @var IntegrationModuleTrait $traitMock */
        $traitMock = $this->getMockForTrait(IntegrationModuleTrait::class);

        /** @var IntegrationModuleEventInterface $eventMock */
        $eventMock = $this->getMock(IntegrationModuleEventInterface::class);

        $traitMock->setPrototypeIntegrationModuleEvent($eventMock);

        $result = $eventMock === $traitMock->getPrototypeIntegrationModuleEvent();

        static::assertTrue($result);
    }
}
