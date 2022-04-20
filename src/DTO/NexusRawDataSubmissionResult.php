<?php

declare(strict_types=1);

namespace App\DTO;

use App\Contract\Config\AppSerializationGroups;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

final class NexusRawDataSubmissionResult
{
    public const ERROR_SOURCE_JSON_DECODE = 'json-decode';
    public const ERROR_SOURCE_JSON_SCHEMA = 'json-schema';
    public const ERROR_SOURCE_ACCESS_TOKEN = 'access-token';

    public function __construct(
        private bool $isValid,
        private ?string $errorSource = null,
        private ?array $errors = null,
    ) {
    }

    #[
        Groups(groups: [AppSerializationGroups::DEFAULT]),
        SerializedName(serializedName: 'isValid'),
    ]
    public function isValid(): bool
    {
        return $this->isValid;
    }

    #[
        Groups(groups: [AppSerializationGroups::DEFAULT]),
        SerializedName(serializedName: 'errorSource'),
    ]
    public function getErrorSource(): ?string
    {
        return $this->errorSource;
    }

    #[
        Groups(groups: [AppSerializationGroups::DEFAULT]),
        SerializedName(serializedName: 'errors'),
    ]
    public function getErrors(): ?array
    {
        return $this->errors;
    }
}
