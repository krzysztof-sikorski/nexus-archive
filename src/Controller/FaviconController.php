<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class FaviconController
{
    #[Route(path: '/favicon.ico', name: 'app_favicon_ico', methods: [Request::METHOD_GET])]
    public function favicon(): Response
    {
        return new Response(null, Response::HTTP_GONE, []);
    }
}
