<?php

declare(strict_types=1);

namespace App\ObjectMapper\Transform;

use App\Dto\Interface\ResponseDto;
use App\Entity\EntityInterface;
use App\Entity\Enum\UriCatalog;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\ObjectMapper\TransformCallableInterface;

/**
 * @implements TransformCallableInterface<ResponseDto, ResponseDto>
 */
final readonly class IriToObject implements TransformCallableInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    /**
     * @throws EntityNotFoundException
     */
    public function __invoke(
        mixed $value,
        object $source,
        ?object $target,
    ): EntityInterface {
        if (preg_match(
            '/^\/api\/([a-zA-Z_.~-]+)\/([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})$/',
            $value,
            $uri,
        )) {
            $entity = UriCatalog::{strtoupper($uri[1])};
            $object = $this->entityManager->getRepository($entity->value)->findOneBy(['uuid' => $uri[2]]);

            return ($object instanceof EntityInterface)
                ? $object
                : throw new EntityNotFoundException($entity, $value)
            ;
        }

        throw new EntityNotFoundException($value);
    }
}
