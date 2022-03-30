<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator) {
    $config = [
        'default_locale' => 'en',
        'translator' => [
            'default_path' => '%kernel.project_dir%/translations',
            'fallbacks' => ['en'],
        ],
    ];
    $containerConfigurator->extension(namespace: 'framework', config: $config);
};
