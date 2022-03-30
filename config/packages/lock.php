<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator) {
    $config = [
        'lock' => '%env(LOCK_DSN)%',
    ];
    $containerConfigurator->extension(namespace: 'framework', config: $config);
};
