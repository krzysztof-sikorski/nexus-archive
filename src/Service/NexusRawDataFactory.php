<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\NexusRawData;
use DateTimeImmutable;

use function array_key_exists;

final class NexusRawDataFactory
{
    public function createFromJsonDataSubmission(object $decodedJsonData): NexusRawData
    {
        $nexusRawData = new NexusRawData();

        $data = get_object_vars($decodedJsonData);

        if (array_key_exists(key: 'requestStartedAt', array: $data)) {
            $requestStartedAt = new DateTimeImmutable($data['requestStartedAt']);
            $nexusRawData->setRequestStartedAt($requestStartedAt);
        }
        if (array_key_exists(key: 'responseCompletedAt', array: $data)) {
            $responseCompletedAt = new DateTimeImmutable($data['responseCompletedAt']);
            $nexusRawData->setResponseCompletedAt($responseCompletedAt);
        }
        if (array_key_exists(key: 'method', array: $data)) {
            $nexusRawData->setMethod($data['method']);
        }
        if (array_key_exists(key: 'url', array: $data)) {
            $nexusRawData->setUrl($data['url']);
        }
        if (array_key_exists(key: 'formData', array: $data)) {
            $nexusRawData->setFormData($data['formData']);
        }
        if (array_key_exists(key: 'responseBody', array: $data)) {
            $nexusRawData->setResponseBody($data['responseBody']);
        }

        return $nexusRawData;
    }
}
