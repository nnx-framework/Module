<?php
/**
 * @link    https://github.com/nnx-framework/module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */

use Nnx\Module\PhpUnit\TestData\TestPaths;

return [
    'modules'                 => [
        'Nnx\\ModuleOptions',
        'Nnx\\Module'
    ],
    'module_listener_options' => [
        'module_paths'      => [
            'Nnx\\ModuleOptions' => TestPaths::getPathToModule(),
        ],
        'config_glob_paths' => [
            __DIR__ . '/config/autoload/{{,*.}global,{,*.}local}.php',
        ],
    ]
];
