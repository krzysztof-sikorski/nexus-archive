<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator) {
    $config = [
        'validation' => [
            'email_validation_mode' => 'html5',
        ],
    ];
    $containerConfigurator->extension(namespace: 'framework', config: $config);

    if ('test' === $containerConfigurator->env()) {
        $config = [
            'validation' => [
                'not_compromised_password' => false,
            ],
        ];
        $containerConfigurator->extension(namespace: 'framework', config: $config);
    }
};


/*
framework:
    validation:
        email_validation_mode: html5

        # Enables validator auto-mapping support.
        # For instance, basic validation constraints will be inferred from Doctrine's metadata.
        #auto_mapping:
        #    App\Entity\: []
*/
