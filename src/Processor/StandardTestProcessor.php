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
use App\Validation\RegexValidations;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
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
readonly class StandardTestProcessor extends Validator implements ProcessorInterface
{
    /**
     * @param ProcessorInterface<EntityInterface, ResponseDto> $persistProcessor
     */
    public function __construct(
        protected EntityManagerInterface $entityManager,
        private ObjectMapperInterface    $objectMapper,
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface       $persistProcessor,
        protected ValidatorInterface     $validator,
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
        if (
            ($operation instanceof Post || $operation instanceof Put)
            && ($data instanceof RequestDto || $data instanceof EntityInterface)
        ) {
            $entityClass = ($operation->getStateOptions() instanceof Options)
                ? ($operation->getStateOptions())->getEntityClass()
                : throw new RuntimeException('Processor: No entity class defined.')
            ;

            if (!is_string($entityClass) || !class_exists($entityClass)) {
                throw new RuntimeException('Processor: Not a valid EntityClass.');
            }

            /** Create target - and source object. */
            $targetObject = ($data instanceof RequestDto)
                ? $this->getTargetObject($operation, $entityClass, $uriVariables)
                : $data;
            $sourceObject = $this->getSourceObject($data);

            $this->specificProcess(object: $sourceObject);

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

    /**
     * @param class-string $entityClass
     * @param array<string, mixed> $uriVariables
     */
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

    /**
     * @throws Exception
     */
    private function getSourceObject(
        object $data,
    ): StdClass
    {
        /**
         * Convert IRI's manually
         * Would be great if able to automate this.
         */
        $source = (new stdClass());
        foreach (get_object_vars($data) as $key => $value) {
            if (
                is_string($value)
                && preg_match(RegexValidations::DATETIME, $value)
            ) {
                $source->$key = new DateTimeImmutable($value);
                continue;
            }

            $source->$key = $value;
        }
        return $source;
    }

    public function specificProcess(object $object): void {}

    public function createResponse(
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
