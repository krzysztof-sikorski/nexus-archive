<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class HomeController
{
    public function __construct(private Environment $twigEnvironment)
    {
    }

    #[Route(path: '/', name: 'app_home', methods: [Request::METHOD_GET])]
    public function index(): Response
    {
        $content = $this->twigEnvironment->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);

        return new Response($content);
    }
}
