<?php

declare(strict_types=1);

namespace App\Service;

use App\Contract\Config\AppRoutes;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class MainMenuService
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function getMenu(): array
    {
        return [
            [
                'name' => 'Home',
                'url' => $this->urlGenerator->generate(name: AppRoutes::HOME),
                'external' => false,
            ],
            [
                'name' => 'Leaderboards',
                'url' => $this->urlGenerator->generate(name: AppRoutes::LEADERBOARDS),
                'external' => false,
            ],
            [
                'name' => 'Submit data',
                'url' => $this->urlGenerator->generate(name: AppRoutes::SUBMIT_JSON),
                'external' => false,
            ],
            [
                'name' => 'Nexus Clash',
                'url' => 'https://www.nexusclash.com/',
                'external' => true,
            ],
            [
                'name' => 'Discord',
                'url' => 'https://discord.gg/zBVwzD3f8v',
                'external' => true,
            ],
        ];
    }
}
