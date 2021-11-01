<?php

declare(strict_types=1);

namespace App\Service;

use App\Contract\Service\ClockInterface;
use App\DTO\NexusRequestLogSubmission;
use App\Repository\NexusRequestLogRepository;
use App\Repository\UserAccessTokenRepository;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use UnexpectedValueException;

final class NexusRequestLogManager
{
    public function __construct(
        private ClockInterface $clock,
        private EntityManagerInterface $entityManager,
        private UserAccessTokenRepository $userAccessTokenRepository,
        private NexusRequestLogRepository $nexusRequestLogRepository,
    ) {
    }

    /**
     * @throws UnexpectedValueException on invalid values
     */
    public function handleSubmission(NexusRequestLogSubmission $nexusRequestLogSubmission): void
    {
        $submittedAt = $this->clock->getCurrentDateTime();
        $userAccessTokenValue = $nexusRequestLogSubmission->getUserAccessToken();
        $nexusRequestLog = $nexusRequestLogSubmission->getNexusRequestLog();

        $userAccessToken = $this->userAccessTokenRepository->findByValue($userAccessTokenValue);
        if (null === $userAccessToken) {
            throw new UnexpectedValueException(sprintf('Invalid access token value: %s', $userAccessTokenValue));
        }

        $request = $nexusRequestLog->getRequest();
        $duplicateLog = $this->nexusRequestLogRepository->findByRequestData($request);
        if (null !== $duplicateLog) {
            throw new UnexpectedValueException(
                sprintf(
                    'Request with (startedAt=%s, id=%s) has already been submitted!',
                    $request->getStartedAt()?->format(DateTimeInterface::ISO8601),
                    $request->getId()
                )
            );
        }

        $submitter = $userAccessToken->getOwner();

        $nexusRequestLog->setSubmittedAt($submittedAt);
        $nexusRequestLog->setSubmitter($submitter);

        $this->entityManager->persist($nexusRequestLog);
        $this->entityManager->flush();
    }
}
