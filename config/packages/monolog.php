<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\Response;

return static function (ContainerConfigurator $containerConfigurator) {
    if ('dev' === $containerConfigurator->env()) {
        $config = [
            // As of Symfony 5.1, deprecations are logged in the dedicated "deprecation" channel when it exists
            'channels' => [
                'deprecation',
            ],
            'handlers' => [
                'deprecation' => [
                    'type' => 'stream',
                    'channels' => [
                        'deprecation',
                    ],
                    'path' => '%kernel.logs_dir%/%kernel.environment%.deprecation.log',
                ],
                'main' => [
                    'type' => 'stream',
                    'path' => '%kernel.logs_dir%/%kernel.environment%.log',
                    'level' => 'debug',
                    'channels' => [
                        '!event',
                    ],
                ],
                'console' => [
                    'type' => 'console',
                    'process_psr_3_messages' => false,
                    'channels' => [
                        '!event',
                        '!doctrine',
                        '!console',
                    ],
                ],
            ],
        ];
        $containerConfigurator->extension(namespace: 'monolog', config: $config);
    }

    if ('prod' === $containerConfigurator->env()) {
        $config = [
            'handlers' => [
                'main' => [
                    'type' => 'fingers_crossed',
                    'action_level' => 'error',
                    'handler' => 'nested',
                    'excluded_http_codes' => [
                        Response::HTTP_NOT_FOUND,
                        Response::HTTP_METHOD_NOT_ALLOWED,
                    ],
                    'buffer_size' => 50, // How many messages should be saved? Prevent memory leaks
                ],
                'nested' => [
                    'type' => 'stream',
                    'path' => 'php://stderr',
                    'level' => 'debug',
                    'formatter' => 'monolog.formatter.json',
                ],
                'console' => [
                    'type' => 'console',
                    'process_psr_3_messages' => false,
                    'channels' => [
                        '!event',
                        '!doctrine',
                    ],
                ],
            ],
        ];
        $containerConfigurator->extension(namespace: 'monolog', config: $config);
    }

    if ('test' === $containerConfigurator->env()) {
        $config = [
            'handlers' => [
                'main' => [
                    'type' => 'fingers_crossed',
                    'action_level' => 'error',
                    'handler' => 'nested',
                    'excluded_http_codes' => [
                        Response::HTTP_NOT_FOUND,
                        Response::HTTP_METHOD_NOT_ALLOWED,
                    ],
                    'channels' => [
                        '!event',
                    ],
                ],
                'nested' => [
                    'type' => 'stream',
                    'path' => '%kernel.logs_dir%/%kernel.environment%.log',
                    'level' => 'debug',
                ],
            ],
        ];
        $containerConfigurator->extension(namespace: 'monolog', config: $config);
    }
};
