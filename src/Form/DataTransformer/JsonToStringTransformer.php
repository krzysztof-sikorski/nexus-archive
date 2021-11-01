<?php

declare(strict_types=1);

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

use function json_decode;
use function json_encode;

final class JsonToStringTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): ?string
    {
        return json_encode($value);
    }

    public function reverseTransform(mixed $value): mixed
    {
        return json_decode($value);
    }
}
