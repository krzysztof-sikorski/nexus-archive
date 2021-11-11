<?php

declare(strict_types=1);

namespace App\Service;

use App\Contract\Service\ClockInterface;
use App\DTO\NexusRawDataSubmissionResult;
use App\Repository\NexusRawDataRepository;
use App\Repository\UserAccessTokenRepository;
use Doctrine\ORM\EntityManagerInterface;

final class NexusRawDataManager
{
    public function __construct(
        private ClockInterface $clock,
        private NexusRawDataFactory $nexusRawDataFactory,
        private EntityManagerInterface $entityManager,
        private UserAccessTokenRepository $userAccessTokenRepository,
        private NexusRawDataRepository $nexusRawDataRepository,
    ) {
    }

    public function handleSubmission(
        string $userAccessTokenValue,
        object $decodedJsonData
    ): NexusRawDataSubmissionResult {
        $submittedAt = $this->clock->getCurrentDateTime();

        $nexusRawData = $this->nexusRawDataFactory->createFromJsonDataSubmission($decodedJsonData);

        $userAccessToken = $this->userAccessTokenRepository->findByValue($userAccessTokenValue);
        if (null === $userAccessToken) {
            $errors = [
                sprintf('Invalid access token value: %s', $userAccessTokenValue),
            ];
            return new NexusRawDataSubmissionResult(
                isValid: false,
                errorSource: NexusRawDataSubmissionResult::ERROR_SOURCE_ACCESS_TOKEN,
                errors: $errors
            );
        }

        $duplicateEntry = $this->nexusRawDataRepository->findByRequestIds($nexusRawData);
        if (null !== $duplicateEntry) {
            $errors = [
                sprintf(
                    'Entry with (sessionId=%s, requestId=%s) has already been submitted!',
                    $nexusRawData->getSessionId(),
                    $nexusRawData->getRequestId(),
                ),
            ];
            return new NexusRawDataSubmissionResult(
                isValid: false,
                errorSource: NexusRawDataSubmissionResult::ERROR_SOURCE_DUPLICATE_ENTRY,
                errors: $errors
            );
        }

        $submitter = $userAccessToken->getOwner();

        $nexusRawData->setSubmittedAt($submittedAt);
        $nexusRawData->setSubmitter($submitter);

        $this->entityManager->persist($nexusRawData);
        $this->entityManager->flush();

        return new NexusRawDataSubmissionResult(isValid: true, errorSource: null, errors: null);
    }
}
