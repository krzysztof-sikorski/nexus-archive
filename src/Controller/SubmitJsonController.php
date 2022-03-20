<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\NexusRawDataSubmissionResult;
use App\Service\NexusRawDataManager;
use App\Service\NexusRawDataValidator;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

use function json_decode;

use const JSON_THROW_ON_ERROR;

final class SubmitJsonController
{
    public function __construct(
        private Environment $twigEnvironment,
        private NexusRawDataValidator $validator,
        private NexusRawDataManager $nexusRawDataManager,
    ) {
    }

    #[Route(path: '/submit-json', name: 'app_submit_json', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function json(Request $request): Response
    {
        if ($request->isMethod(method: Request::METHOD_POST)) {
            $userAccessTokenValue = $request->request->get(key: 'userAccessToken');
            $jsonData = $request->request->get(key: 'jsonData');

            try {
                $decodedJsonData = json_decode(json: $jsonData, flags: JSON_THROW_ON_ERROR);
            } catch (JsonException $e) {
                $responseData = new NexusRawDataSubmissionResult(
                    isValid: false,
                    errorSource: NexusRawDataSubmissionResult::ERROR_SOURCE_JSON_DECODE,
                    errors: [$e->getMessage()]
                );
                return new JsonResponse(data: $responseData, status: Response::HTTP_BAD_REQUEST);
            }

            $validationResult = $this->validator->validate($decodedJsonData);
            if (false === $validationResult->isValid()) {
                return new JsonResponse(data: $validationResult, status: Response::HTTP_BAD_REQUEST);
            }

            $submissionResult = $this->nexusRawDataManager->handleSubmission($userAccessTokenValue, $decodedJsonData);

            return new JsonResponse(data: $submissionResult, status: Response::HTTP_CREATED);
        }

        $content = $this->twigEnvironment->render('submit-json/index.html.twig');

        return new Response($content);
    }
}
