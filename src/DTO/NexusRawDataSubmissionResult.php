<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

final class NexusRawDataSubmissionResult implements JsonSerializable
{
    public const ERROR_SOURCE_JSON_DECODE = 'json-decode';
    public const ERROR_SOURCE_JSON_SCHEMA = 'json-schema';
    public const ERROR_SOURCE_ACCESS_TOKEN = 'access-token';
    public const ERROR_SOURCE_DUPLICATE_ENTRY = 'duplicate-entry';

    public function __construct(
        private bool $isValid,
        private ?string $errorSource = null,
        private ?array $errors = null,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'isValid' => $this->isValid(),
            'errorSource' => $this->getErrorSource(),
            'errors' => $this->getErrors(),
        ];
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getErrorSource(): ?string
    {
        return $this->errorSource;
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }
}
