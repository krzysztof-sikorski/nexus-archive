<?php

declare(strict_types=1);

namespace App\Controller;

use App\Contract\Config\AppParameters;
use App\Contract\Config\AppRoutes;
use App\DTO\NexusRawDataSubmissionResult;
use App\Service\NexusRawDataManager;
use App\Service\NexusRawDataValidator;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Twig\Environment;

use function json_decode;

use const JSON_THROW_ON_ERROR;

final class SubmitJsonController
{
    public function __construct(
        private Environment $twigEnvironment,
        private NexusRawDataValidator $validator,
        private NexusRawDataManager $nexusRawDataManager,
        private SerializerInterface $serializer,
    ) {
    }

    #[Route(path: '/submit-json', name: AppRoutes::SUBMIT_JSON, methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function json(
        Request $request
    ): Response {
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
                return $this->createJsonResponse(data: $responseData, status: Response::HTTP_BAD_REQUEST);
            }

            $validationResult = $this->validator->validate(decodedJsonData: $decodedJsonData);
            if (false === $validationResult->isValid()) {
                return $this->createJsonResponse(data: $validationResult, status: Response::HTTP_BAD_REQUEST);
            }

            $submissionResult = $this->nexusRawDataManager->handleSubmission(
                userAccessTokenValue: $userAccessTokenValue,
                decodedJsonData: $decodedJsonData
            );

            return $this->createJsonResponse(data: $submissionResult, status: Response::HTTP_CREATED);
        }

        $content = $this->twigEnvironment->render(name: 'submit-json/index.html.twig');

        return new Response($content);
    }

    private function createJsonResponse(mixed $data, int $status): Response
    {
        $serializedData = $this->serializer->serialize(
            data: $data,
            format: JsonEncoder::FORMAT,
            context: AppParameters::SERIALIZER_DEFAULT_CONTEXT,
        );
        return new JsonResponse(data: $serializedData, status: $status, json: true);
    }
}
