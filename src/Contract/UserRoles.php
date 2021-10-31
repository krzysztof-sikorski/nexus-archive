<?php

declare(strict_types=1);

namespace App\Contract;

use function in_array;

// TODO convert into native enum when PHP 8.1 is released
final class UserRoles
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    private const ALL_ROLES = [
        self::ROLE_USER,
        self::ROLE_ADMIN,
    ];

    public static function isValidRole(string $role): bool
    {
        return in_array($role, self::ALL_ROLES, true);
    }
}
