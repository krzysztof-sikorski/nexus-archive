<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\NexusRawDataSubmissionResult;
use App\Kernel;
use Opis\JsonSchema\Errors\ErrorFormatter;
use Opis\JsonSchema\Validator;

final class NexusRawDataValidator
{
    private const SCHEMA_ID = 'https://nexus-archive.zerozero.pl/submit-json';
    private Validator $validator;
    private ErrorFormatter $errorFormatter;

    public function __construct(private Kernel $kernel)
    {
        $this->validator = new Validator();
        $this->validator->resolver()->registerFile(
            id: self::SCHEMA_ID,
            file: $this->getSchemaPath(),
        );
        $this->errorFormatter = new ErrorFormatter();
    }

    private function getSchemaPath(): string
    {
        return $this->kernel->getProjectDir() . '/assets/NexusRawDataJsonSchema.json';
    }

    public function validate(mixed $decodedJsonData): NexusRawDataSubmissionResult
    {
        $validationResult = $this->validator->validate(data: $decodedJsonData, schema: self::SCHEMA_ID);
        if ($validationResult->isValid()) {
            return new NexusRawDataSubmissionResult(isValid: true, errorSource: null, errors: null);
        }

        $errors = $this->errorFormatter->format(error: $validationResult->error(), multiple: true);
        return new NexusRawDataSubmissionResult(
            isValid: false,
            errorSource: NexusRawDataSubmissionResult::ERROR_SOURCE_JSON_SCHEMA,
            errors: $errors
        );
    }
}
