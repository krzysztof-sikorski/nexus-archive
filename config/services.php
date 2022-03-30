<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator) {
    $containerConfigurator->import(
        resource: 'packages/*.php',
        type: 'glob',
    );

    $servicesConfigurator = $containerConfigurator->services();

    $defaultsConfigurator = $servicesConfigurator->defaults();
    $defaultsConfigurator->autowire(autowired: true);
    $defaultsConfigurator->autoconfigure(autoconfigured: true);

    $prototypeConfigurator = $servicesConfigurator->load(
        namespace: 'App\\',
        resource: __DIR__ . '/../src/',
    );
    $excludes = [
        __DIR__ . '/../src/DependencyInjection/',
        __DIR__ . '/../src/Entity/',
        __DIR__ . '/../src/Kernel.php',
    ];
    $prototypeConfigurator->exclude(excludes: $excludes);

    $prototypeConfigurator = $servicesConfigurator->load(
        namespace: 'App\\Controller\\',
        resource: __DIR__ . '/../src/Controller/',
    );
    $prototypeConfigurator->tag(name: 'controller.service_arguments');
};
