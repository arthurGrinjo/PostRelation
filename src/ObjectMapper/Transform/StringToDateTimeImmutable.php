<?php

declare(strict_types=1);

namespace App\ObjectMapper\Transform;

use App\Dto\Interface\ResponseDto;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use InvalidArgumentException;
use Symfony\Component\ObjectMapper\TransformCallableInterface;

/**
 * @implements TransformCallableInterface<ResponseDto, ResponseDto>
 */
final readonly class StringToDateTimeImmutable implements TransformCallableInterface
{
    public function __construct(
        private string $format = DateTimeInterface::RFC3339,
    ) {}

    /**
     * @throws Exception
     */
    public function __invoke(mixed $value, object $source, ?object $target): DateTimeImmutable
    {
        $date = (is_string($value))
            ? DateTimeImmutable::createFromFormat($this->format, $value)
            : throw new Exception('Unable to convert value to DateTimeImmutable')
        ;

        if ($date === false) {
            throw new InvalidArgumentException(
                sprintf('Cannot parse date "%s" with format "%s".', $value, $this->format)
            );
        }

        return $date;
    }
}
