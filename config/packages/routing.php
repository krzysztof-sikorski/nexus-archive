<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator) {
    $config = [
        'router' => [
            'utf8' => true,
        ],
    ];
    $containerConfigurator->extension(namespace: 'framework', config: $config);

    if ('prod' === $containerConfigurator->env()) {
        $config = [
            'router' => [
                'strict_requirements' => null,
            ],
        ];
        $containerConfigurator->extension(namespace: 'framework', config: $config);
    }
};
