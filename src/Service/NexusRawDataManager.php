<?php

declare(strict_types=1);

namespace App\Service;

use App\Contract\Service\ClockInterface;
use App\DTO\NexusRawDataSubmissionResult;
use App\Repository\UserAccessTokenRepository;
use Doctrine\ORM\EntityManagerInterface;

final class NexusRawDataManager
{
    public function __construct(
        private ClockInterface $clock,
        private NexusRawDataFactory $nexusRawDataFactory,
        private EntityManagerInterface $entityManager,
        private UserAccessTokenRepository $userAccessTokenRepository,
    ) {
    }

    public function handleSubmission(
        string $userAccessTokenValue,
        object $decodedJsonData
    ): NexusRawDataSubmissionResult {
        $submittedAt = $this->clock->getCurrentDateTime();

        $nexusRawData = $this->nexusRawDataFactory->createFromJsonDataSubmission(decodedJsonData: $decodedJsonData);

        $userAccessToken = $this->userAccessTokenRepository->findByValue(value: $userAccessTokenValue);
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

        $submitter = $userAccessToken->getOwner();

        $nexusRawData->setCreatedAt(createdAt: $submittedAt);
        $nexusRawData->setLastModifiedAt(lastModifiedAt: $submittedAt);
        $nexusRawData->setSubmitter(submitter: $submitter);

        $this->entityManager->persist($nexusRawData);
        $this->entityManager->flush();

        return new NexusRawDataSubmissionResult(isValid: true, errorSource: null, errors: null);
    }
}
