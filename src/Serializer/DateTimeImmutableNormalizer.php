<?php

declare(strict_types=1);

namespace App\Serializer;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class DateTimeImmutableNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * @param string $data
     * @throws Exception
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): DateTimeImmutable
    {
        return new DateTimeImmutable($data);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        // We support denormalizing if the data is a string and the target type is DateTimeImmutable
        return is_string($data) && $type === DateTimeImmutable::class;
    }

    /**
     * @throws Exception
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): string
    {
        return ($data instanceof DateTimeImmutable)
            ? $data->format(DateTimeInterface::RFC3339)
            : throw new Exception('Normalization error: not an instance of DateTimeImmutable');
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof DateTimeImmutable;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [DateTimeImmutable::class => true];
    }
}
