<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator) {
    $config = [
        'migrations_paths' => [
            // namespace is arbitrary but should be different from App\Migrations
            // as migrations classes should NOT be autoloaded
            'DoctrineMigrations' => '%kernel.project_dir%/migrations',
        ],
        'enable_profiler' => '%kernel.debug%',
    ];
    $containerConfigurator->extension(namespace: 'doctrine_migrations', config: $config);
};
