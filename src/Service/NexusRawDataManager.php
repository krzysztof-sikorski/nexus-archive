<?php

declare(strict_types=1);

namespace App\Service;

use App\Contract\Service\ClockInterface;
use App\DTO\NexusRawDataSubmissionResult;
use App\Service\Repository\UserAccessTokenRepository;
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
        $timeZone = $this->clock->getUtcTimeZone();
        $currentDateTime = $this->clock->getCurrentDateTime();

        $nexusRawData = $this->nexusRawDataFactory->createFromJsonDataSubmission(
            decodedJsonData: $decodedJsonData,
            timeZone: $timeZone
        );

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

        $owner = $userAccessToken->getOwner();

        $nexusRawData->setCreatedAt(createdAt: $currentDateTime);
        $nexusRawData->setLastModifiedAt(lastModifiedAt: $currentDateTime);
        $nexusRawData->setOwner(owner: $owner);

        $this->entityManager->persist($nexusRawData);
        $this->entityManager->flush();

        return new NexusRawDataSubmissionResult(isValid: true, errorSource: null, errors: null);
    }
}
