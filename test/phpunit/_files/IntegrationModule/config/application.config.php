<?php

use Nnx\Module\PhpUnit\TestData\TestPaths;
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
        'module_paths'      => [
            'Nnx\\Module' => TestPaths::getPathToModule(),

            Service\Module1\Module::MODULE_NAME => TestPaths::getPathToIntegrationModuleTestServiceDir() . 'module1',
            Service\Module2\Module::MODULE_NAME => TestPaths::getPathToIntegrationModuleTestServiceDir() . 'module2',
            Service\Module3\Module::MODULE_NAME => TestPaths::getPathToIntegrationModuleTestServiceDir() . 'module3',
            Service\Service\Module::MODULE_NAME => TestPaths::getPathToIntegrationModuleTestServiceDir() . 'service',

            CustomService\Module1\Module::MODULE_NAME => TestPaths::getPathToIntegrationModuleTestCustomServiceDir(). 'module1',
            CustomService\Module2\Module::MODULE_NAME => TestPaths::getPathToIntegrationModuleTestCustomServiceDir() . 'module2',
            CustomService\Module3\Module::MODULE_NAME => TestPaths::getPathToIntegrationModuleTestCustomServiceDir() . 'module3',
            CustomService\Service\Module::MODULE_NAME => TestPaths::getPathToIntegrationModuleTestCustomServiceDir() . 'service',
        ],
        'config_glob_paths' => [
            __DIR__ . '/config/autoload/{{,*.}global,{,*.}local}.php',
        ],
    ]
];
