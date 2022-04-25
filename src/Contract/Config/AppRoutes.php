<?php

declare(strict_types=1);

namespace App\Contract\Config;

// TODO convert into native enum when PHP 8.1 is released
final class AppRoutes
{
    public const HOME = 'app_home';
    public const FAVICON_ICO = 'app_favicon_ico';
    public const LEADERBOARDS = 'app_leaderboards';
    public const LOGIN = 'app_login';
    public const LOGOUT = 'app_logout';
    public const SUBMIT_JSON = 'app_submit_json';
    public const EASYADMIN = 'app_easyadmin';
}
