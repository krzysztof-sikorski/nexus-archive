<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\NexusRequestLogSubmission;
use App\Form\SubmitFormType;
use App\Service\NexusRequestLogManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Twig\Environment;

final class SubmitController
{
    public function __construct(
        private Environment $twigEnvironment,
        private FormFactoryInterface $formFactory,
        private NexusRequestLogManager $nexusRequestLogManager,
        private SerializerInterface $serializer,
    ) {
    }

    #[Route(path: '/submit', name: 'submit')]
    public function index(Request $request): Response
    {
        $form = $this->formFactory->create(
            type: SubmitFormType::class,
            options: [
                'csrf_protection' => false,
            ],
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var NexusRequestLogSubmission $formData */
            $nexusRequestLogSubmission = $form->getData();

            try {
                $this->nexusRequestLogManager->handleSubmission($nexusRequestLogSubmission);
            } catch (\UnexpectedValueException $exception) {
                return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST, []);
            }

            $content = $this->serializer->serialize($nexusRequestLogSubmission, 'json');
            return new JsonResponse(data: $content, status: Response::HTTP_CREATED, json: true);
        }

        $content = $this->twigEnvironment->render('submit/index.html.twig', [
            'form' => $form->createView(),
        ]);

        return new Response($content);
    }
}
