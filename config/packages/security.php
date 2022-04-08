<?php

declare(strict_types=1);

use App\Contract\UserRoles;
use App\Entity\User;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Config\SecurityConfig;

return static function (SecurityConfig $securityConfig, ContainerConfigurator $containerConfigurator) {
    $userProviderName = 'app_user_provider';
    $userEntityLookupProperty = 'username';
    $routeKeyHome = 'app_home';
    $routeKeyLogin = 'app_login';
    $routeKeyLogout = 'app_logout';

    $securityConfig->enableAuthenticatorManager(value: true);

    $authPasswordHasherConfig = $securityConfig->passwordHasher(class: PasswordAuthenticatedUserInterface::class);
    $authPasswordHasherConfig->algorithm(value: 'auto');

    $userPasswordHasherConfig = $securityConfig->passwordHasher(class: User::class);
    $userPasswordHasherConfig->algorithm(value: 'auto');

    $securityConfig->passwordHasher(class: User::class)->algorithm(value: 'auto');

    $entityConfig = $securityConfig->provider(name: $userProviderName)->entity();
    $entityConfig->class(User::class);
    $entityConfig->property(value: $userEntityLookupProperty);

    $firewallConfig = $securityConfig->firewall(name: 'dev');
    $firewallConfig->pattern(value: '^/(_(profiler|wdt)|css|images|js)/');
    $firewallConfig->security(value: false);

    $firewallConfig = $securityConfig->firewall(name: 'main');
    $firewallConfig->lazy(value: true);
    $firewallConfig->provider(value: $userProviderName);

    $formLoginConfig = $firewallConfig->formLogin();
    $formLoginConfig->loginPath(value: $routeKeyLogin);
    $formLoginConfig->checkPath(value: $routeKeyLogin);
    $formLoginConfig->enableCsrf(value: true);
    $formLoginConfig->defaultTargetPath(value: $routeKeyHome);
    $formLoginConfig->alwaysUseDefaultTargetPath(value: true);

    $firewallConfig->loginThrottling(); // enable with default values

    $firewallConfig->logout()->path(value: $routeKeyLogout);

    $accessControlConfig = $securityConfig->accessControl();
    $accessControlConfig->path(value: '^/admin');
    $accessControlConfig->roles(value: [UserRoles::ROLE_ADMIN]);

    if ('test' === $containerConfigurator->env()) {
        // By default, password hashers are resource intensive and take time. This is
        // important to generate secure password hashes. In tests however, secure hashes
        // are not important, waste resources and increase test times. The following
        // reduces the work factor to the lowest possible values.
        $authPasswordHasherConfig->algorithm(value: 'auto');
        $authPasswordHasherConfig->cost(value: 4); // Lowest possible value for bcrypt
        $authPasswordHasherConfig->timeCost(value: 3); // Lowest possible value for argon
        $authPasswordHasherConfig->memoryCost(value: 10); // Lowest possible value for argon
    }
};
