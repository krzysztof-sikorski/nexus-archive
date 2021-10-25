<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController
{
    private Environment $twigEnvironment;

    public function __construct(Environment $twigEnvironment)
    {
        $this->twigEnvironment = $twigEnvironment;
    }

    #[Route('/', name: 'home')]
    public function index(Environment $twigEnvironment): Response
    {
        $content = $this->twigEnvironment->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);

        return new Response($content);
    }

    #[Route('/favicon.ico', name: 'favicon_ico')]
    public function favicon(): Response
    {
        return new Response(null, Response::HTTP_GONE, []);
    }
}
