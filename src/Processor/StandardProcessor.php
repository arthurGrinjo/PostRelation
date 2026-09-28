<?php

declare(strict_types=1);

namespace App\Processor;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Interface\RequestDto;
use App\Dto\Interface\ResponseDto;
use App\Entity\EntityInterface;
use App\Entity\Enum\UriCatalog;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use RuntimeException;
use stdClass;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

/**
 * @implements ProcessorInterface<EntityInterface, ResponseDto>
 */
readonly class StandardProcessor extends Validator implements ProcessorInterface
{
    /**
     * @param ProcessorInterface<EntityInterface, ResponseDto> $persistProcessor
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ObjectMapperInterface $objectMapper,
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        protected ValidatorInterface $validator,
    ) {
        parent::__construct($validator);
    }

    /**
     * @throws Throwable
     */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): ResponseDto
    {
        if (($operation instanceof Post || $operation instanceof Put) && $data instanceof RequestDto) {
            $entityClass = ($operation->getStateOptions() instanceof Options)
                ? ($operation->getStateOptions())->getEntityClass()
                : throw new RuntimeException('Processor: No entity class defined.')
            ;

            if (!is_string($entityClass) || !class_exists($entityClass)) {
                throw new RuntimeException('Processor: Not a valid EntityClass.');
            }

            /** Create target - and source object. */
            $targetObject = $this->getTargetObject($operation, $entityClass, $uriVariables);
            $sourceObject = $this->getSourceObject($data);

            $this->objectMapper->map(
                source: $sourceObject,
                target: $targetObject,
            );

            $this->persistProcessor->process(
                data: $targetObject,
                operation: $operation,
                uriVariables: $uriVariables,
                context: $context
            );

            return $this->createResponse(
                operation: $operation,
                entity: $targetObject,
                entityClass: $entityClass,
            );
        }

        throw new RuntimeException('Cannot process this request.', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    private function getTargetObject(
        Operation $operation,
        string $entityClass,
        array $uriVariables = [],
    ): EntityInterface {
        /** return EntityInterface */
        $entity = ($operation instanceof Post)
            ? new $entityClass
            : $this->entityManager->getRepository($entityClass)->findOneBy($uriVariables)
        ;

        return ($entity instanceof EntityInterface)
            ? $entity
            : throw new EntityNotFoundException($entityClass);
    }

    private function getSourceObject(
        mixed $data,
    ): StdClass
    {
        /**
         * Convert IRI's manually
         * Would be great if able to automate this.
         */
        $source = (new stdClass());
        foreach (get_object_vars($data) as $key => $value) {
            if (preg_match(
                '/^\/api\/([a-zA-Z_.~-]+)\/([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})$/',
                $value,
                $uri,
            )) {
                $objectClass = UriCatalog::{strtoupper($uri[1])};
                $object = $this->entityManager->getRepository($objectClass->value)->findOneBy(['uuid' => $uri[2]]);

                $value =  ($object instanceof EntityInterface)
                    ? $object
                    : throw new EntityNotFoundException($objectClass, $value)
                ;
            }
            $source->$key = $value;
        }
        return $source;
    }

    private function createResponse(
        Operation $operation,
        EntityInterface $entity,
        string $entityClass,
    ): ResponseDto {
        $responseDto = (
            is_array($operation->getOutput())
            && array_key_exists('class', $operation->getOutput())
            && is_string($operation->getOutput()['class'])
            && class_exists($operation->getOutput()['class'])
        )
            ? $this->objectMapper->map(
                source: $entity,
                target: $operation->getOutput()['class'],
            )
            : throw new RuntimeException(sprintf('Invalid OutputClass: %s', $entityClass))
        ;

        return ($responseDto instanceof ResponseDto)
            ? $responseDto
            : throw new RuntimeException(sprintf('Invalid response dto: %s', $entityClass))
        ;
    }
}
