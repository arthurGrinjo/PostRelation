<?php

declare(strict_types=1);

namespace App\ObjectMapper;

use Override;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

#[AsDecorator(decorates: ObjectMapperInterface::class)]
readonly class DecorateObjectMapper implements ObjectMapperInterface
{
    public function __construct(
        private ObjectMapperInterface $objectMapper,
    ) {}

    #[Override]
    public function map(
        object $source,
        object|string|null $target = null,
        array $context = [],
    ): object {
        /**
         * Unable to get here... map: true/false in operation doesn't change the behaviour..
         */
        return $this->objectMapper->map($source, $target);
    }
}
