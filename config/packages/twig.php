<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator) {
    $config = [
        'default_path' => '%kernel.project_dir%/templates',
    ];
    $containerConfigurator->extension(namespace: 'twig', config: $config);

    if ('test' === $containerConfigurator->env()) {
        $config = [
            'strict_variables' => true,
        ];
        $containerConfigurator->extension(namespace: 'twig', config: $config);
    }
};
