<?php

declare(strict_types=1);

namespace App\DataTransformer;

use JsonException;
use Symfony\Component\Form\DataTransformerInterface;

use const JSON_THROW_ON_ERROR;

final class JsonToStringTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): ?string
    {
        try {
            return json_encode($value, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return null;
        }
    }

    public function reverseTransform(mixed $value): mixed
    {
        if (null === $value) {
            return null;
        }

        try {
            return json_decode($value, flags: JSON_OBJECT_AS_ARRAY | JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return null;
        }
    }
}
