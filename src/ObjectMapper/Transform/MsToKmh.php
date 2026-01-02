<?php

declare(strict_types=1);

namespace App\ObjectMapper\Transform;

use App\Dto\Interface\ResponseDto;
use Exception;
use Symfony\Component\ObjectMapper\TransformCallableInterface;

/**
 * @implements TransformCallableInterface<ResponseDto, ResponseDto>
 */
final readonly class MsToKmh implements TransformCallableInterface
{
    private const float FACTOR = 3.6;

    /**
     * @throws Exception
     */
    public function __invoke(
        mixed $value,
        object $source,
        ?object $target,
    ): float {
        return (is_int($value) || is_float($value))
            ? round(
                num: $value * self::FACTOR,
                precision: 3
            )
            : throw new Exception('Unable to convert value from ms into kmh')
        ;
    }
}
