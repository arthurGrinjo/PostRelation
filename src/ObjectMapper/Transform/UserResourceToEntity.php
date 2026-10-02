<?php

declare(strict_types=1);

namespace App\ObjectMapper\Transform;

use App\Dto\User\Response\UserResponseDto;
use App\Entity\User as UserEntity;
use App\Repository\UserRepository;
use Symfony\Component\ObjectMapper\Exception\MappingTransformException;
use Symfony\Component\ObjectMapper\TransformCallableInterface;

/**
 * @implements TransformCallableInterface<object, object>
 */
final readonly class UserResourceToEntity implements TransformCallableInterface
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function __invoke(mixed $value, object $source, ?object $target): UserEntity
    {
        if (!$value instanceof UserResponseDto) {
            throw new MappingTransformException(sprintf('Expected "%s", got "%s".', UserResponseDto::class, get_debug_type($value)));
        }

        return $this->userRepository->findOneBy(['uuid' => $value->uuid])
            ?? throw new MappingTransformException(sprintf('User "%s" not found.', $value->uuid));
    }
}
