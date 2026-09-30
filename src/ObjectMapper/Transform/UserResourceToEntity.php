<?php

declare(strict_types=1);

namespace App\ObjectMapper\Transform;

use App\ApiResource\User as UserResource;
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
        if (!$value instanceof UserResource) {
            throw new MappingTransformException(sprintf('Expected "%s", got "%s".', UserResource::class, get_debug_type($value)));
        }

        return $this->userRepository->findOneBy(['uuid' => $value->uuid])
            ?? throw new MappingTransformException(sprintf('User "%s" not found.', $value->uuid));
    }
}
