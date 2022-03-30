<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routingConfigurator) {
    $routingConfigurator->import(
        resource: __DIR__ . '/../src/Controller/',
        type: 'annotation',
    );

    $routingConfigurator->import(
        resource: __DIR__ . '/../src/Kernel.php',
        type: 'annotation',
    );

    $routeConfigurator = $routingConfigurator->add(name: 'app_logout', path: '/logout');
    $methods = [
        Request::METHOD_GET,
        Request::METHOD_POST,
    ];
    $routeConfigurator->methods(methods: $methods);

    if ('dev' === $routingConfigurator->env()) {
        $importConfigurator = $routingConfigurator->import(
            resource: '@FrameworkBundle/Resources/config/routing/errors.xml',
        );
        $importConfigurator->prefix(prefix: '/_error');

        $importConfigurator = $routingConfigurator->import(
            resource: '@WebProfilerBundle/Resources/config/routing/wdt.xml',
        );
        $importConfigurator->prefix(prefix: '/_wdt');

        $importConfigurator = $routingConfigurator->import(
            resource: '@WebProfilerBundle/Resources/config/routing/profiler.xml',
        );
        $importConfigurator->prefix(prefix: '/_profiler');
    }
};
