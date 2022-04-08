<?php

declare(strict_types=1);

use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $frameworkConfig) {
    $frameworkConfig->defaultLocale('en');

    $translatorConfig = $frameworkConfig->translator();
    $translatorConfig->defaultPath(value: '%kernel.project_dir%/translations');
    $translatorConfig->fallbacks(['en']);
};
