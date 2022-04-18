<?php

declare(strict_types=1);

namespace App\Service;

use App\Doctrine\Entity\PageView;
use DateTimeImmutable;
use DateTimeZone;

use function array_key_exists;

final class NexusRawDataFactory
{
    public function createFromJsonDataSubmission(object $decodedJsonData, DateTimeZone $timeZone): PageView
    {
        $pageView = new PageView();

        $data = get_object_vars(object: $decodedJsonData);

        if (array_key_exists(key: 'requestStartedAt', array: $data)) {
            $requestStartedAt = new DateTimeImmutable(datetime: $data['requestStartedAt']);
            $requestStartedAt->setTimezone(timezone: $timeZone);
            $pageView->setRequestStartedAt(requestStartedAt: $requestStartedAt);
        }
        if (array_key_exists(key: 'responseCompletedAt', array: $data)) {
            $responseCompletedAt = new DateTimeImmutable(datetime: $data['responseCompletedAt']);
            $responseCompletedAt->setTimezone(timezone: $timeZone);
            $pageView->setResponseCompletedAt(responseCompletedAt: $responseCompletedAt);
        }
        if (array_key_exists(key: 'method', array: $data)) {
            $pageView->setMethod(method: $data['method']);
        }
        if (array_key_exists(key: 'url', array: $data)) {
            $pageView->setUrl(url: $data['url']);
        }
        if (array_key_exists(key: 'formData', array: $data)) {
            $pageView->setFormData(formData: $data['formData']);
        }
        if (array_key_exists(key: 'responseBody', array: $data)) {
            $pageView->setResponseBody(responseBody: $data['responseBody']);
        }

        return $pageView;
    }
}
