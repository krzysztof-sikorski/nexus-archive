<?php

declare(strict_types=1);

use App\Service\MainMenuService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\TwigConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (TwigConfig $twigConfig, ContainerConfigurator $containerConfigurator) {
    $twigConfig->defaultPath(value: '%kernel.project_dir%/templates');

    $mainMenuConfig = $twigConfig->global(key: 'mainMenu');
    $mainMenuConfig->value(value: service(serviceId: MainMenuService::class));

    if ('test' === $containerConfigurator->env()) {
        $twigConfig->strictVariables(value: true);
    }
};
