<?php

declare(strict_types=1);

use App\Contract\UserRoles;
use App\Entity\User;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

return static function (ContainerConfigurator $containerConfigurator) {
    $config = [
        'enable_authenticator_manager' => true,
        'password_hashers' => [
            PasswordAuthenticatedUserInterface::class => 'auto',
            User::class => [
                'algorithm' => 'auto',
            ],
        ],
        'providers' => [
            'app_user_provider' => [
                'entity' => [
                    'class' => User::class,
                    'property' => 'username',
                ],
            ],
        ],
        'firewalls' => [
            'dev' => [
                'pattern' => '^/(_(profiler|wdt)|css|images|js)/',
                'security' => false,
            ],
            'main' => [
                'lazy' => true,
                'provider' => 'app_user_provider',
                'form_login' => [
                    'login_path' => 'app_login',
                    'check_path' => 'app_login',
                    'enable_csrf' => true,
                    'default_target_path' => 'app_home',
                    'always_use_default_target_path' => true,
                ],
                'login_throttling' => null,
                'logout' => [
                    'path' => 'app_logout',
                ],
            ],
        ],
        'access_control' => [
            [
                'path' => '^/admin',
                'roles' => [UserRoles::ROLE_ADMIN],
            ],
        ],
    ];
    $containerConfigurator->extension(namespace: 'security', config: $config);

    if ('test' === $containerConfigurator->env()) {
        $config = [
            'password_hashers' => [
                // By default, password hashers are resource intensive and take time. This is
                // important to generate secure password hashes. In tests however, secure hashes
                // are not important, waste resources and increase test times. The following
                // reduces the work factor to the lowest possible values.
                PasswordAuthenticatedUserInterface::class => [
                    'algorithm' => 'auto',
                    'cost' => 4, // Lowest possible value for bcrypt
                    'time_cost' => 3, // Lowest possible value for argon
                    'memory_cost' => 10, // Lowest possible value for argon
                ],
            ],
        ];
        $containerConfigurator->extension(namespace: 'security', config: $config);
    }
};
