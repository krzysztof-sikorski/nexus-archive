<?php

declare(strict_types=1);

namespace App\Contract;

// TODO convert into native enum when PHP 8.1 is released
final class UserRoles
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
}
