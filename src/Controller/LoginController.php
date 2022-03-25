<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

final class LoginController
{
    public function __construct(private Environment $twigEnvironment)
    {
    }

    #[Route('/login', name: 'app_login')]
    public function index(
        AuthenticationUtils $authenticationUtils
    ): Response {
        $context = [
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'last_username' => $authenticationUtils->getLastUsername(),
        ];
        $content = $this->twigEnvironment->render(name: 'login/index.html.twig', context: $context);

        return new Response(content: $content);
    }
}
