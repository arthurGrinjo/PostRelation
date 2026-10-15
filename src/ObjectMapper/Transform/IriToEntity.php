<?php

declare(strict_types=1);

namespace App\ObjectMapper\Transform;

use App\Entity\Enum\UriCatalog;
use App\Entity\User as UserEntity;
use App\Validation\RegexValidations;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\ObjectMapper\Exception\MappingTransformException;
use Symfony\Component\ObjectMapper\TransformCallableInterface;

/**
 * @implements TransformCallableInterface<object, object>
 */
final readonly class IriToEntity implements TransformCallableInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(mixed $value, object $source, ?object $target): UserEntity
    {
        if (
            is_string($value)
            && preg_match(RegexValidations::IRI, $value, $uri)
        ) {
            /** @var UriCatalog $objectClass */
            $objectClass = UriCatalog::{strtoupper($uri[1])};

            return $this->entityManager->getRepository($objectClass->value)->findOneBy(['uuid' => $uri[2]])
                ?? throw new MappingTransformException(sprintf('IriToEntity: "%s" not found.', $value->uuid));
        }

        throw new MappingTransformException(sprintf('IriToEntity: No valid input "%s".', $value));
    }
}
