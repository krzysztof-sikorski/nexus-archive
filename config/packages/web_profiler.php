<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator) {
    if ('dev' === $containerConfigurator->env()) {
        $config = [
            'toolbar' => true,
            'intercept_redirects' => false,
        ];
        $containerConfigurator->extension(namespace: 'web_profiler', config: $config);

        $config = [
            'profiler' => [
                'only_exceptions' => false,
            ],
        ];
        $containerConfigurator->extension(namespace: 'framework', config: $config);
    }

    if ('test' === $containerConfigurator->env()) {
        $config = [
            'toolbar' => false,
            'intercept_redirects' => false,
        ];
        $containerConfigurator->extension(namespace: 'web_profiler', config: $config);

        $config = [
            'profiler' => [
                'collect' => false,
            ],
        ];
        $containerConfigurator->extension(namespace: 'framework', config: $config);
    }
};
