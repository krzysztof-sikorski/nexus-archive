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

    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(): Response
    {
        $content = $this->twigEnvironment->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);

        return new Response($content);
    }
}
