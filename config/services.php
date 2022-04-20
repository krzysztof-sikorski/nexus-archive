<?php

declare(strict_types=1);

use App\Contract\Config\AppParameters;
use App\Service\Serializer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Serializer\SerializerInterface;

return static function (ContainerConfigurator $containerConfigurator) {
    $containerConfigurator->import(
        resource: __DIR__ . '/packages/*.php',
        type: AppParameters::CONFIGURATOR_IMPORT_TYPE_GLOB,
    );

    $envConfigDir = __DIR__ . '/packages/' . $containerConfigurator->env();
    if (is_dir($envConfigDir)) {
        $containerConfigurator->import(
            resource: $envConfigDir . '/*.php',
            type: AppParameters::CONFIGURATOR_IMPORT_TYPE_GLOB,
        );
    }

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

    $servicesConfigurator->set(id: Serializer::class)->decorate(id: SerializerInterface::class);
};
