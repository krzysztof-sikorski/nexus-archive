<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FaviconController
{
    #[Route(path: '/favicon.ico', name: 'favicon_ico', methods: ['GET'])]
    public function favicon(): Response
    {
        return new Response(null, Response::HTTP_GONE, []);
    }
}
