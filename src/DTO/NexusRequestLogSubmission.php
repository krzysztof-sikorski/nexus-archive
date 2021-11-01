<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\NexusRequestLog;

final class NexusRequestLogSubmission
{
    public function __construct(
        private ?string $userAccessToken = null,
        private ?NexusRequestLog $nexusRequestLog = null
    ) {
    }

    public function getUserAccessToken(): ?string
    {
        return $this->userAccessToken;
    }

    public function setUserAccessToken(?string $userAccessToken): void
    {
        $this->userAccessToken = $userAccessToken;
    }

    public function getNexusRequestLog(): ?NexusRequestLog
    {
        return $this->nexusRequestLog;
    }

    public function setNexusRequestLog(?NexusRequestLog $nexusRequestLog): void
    {
        $this->nexusRequestLog = $nexusRequestLog;
    }
}
