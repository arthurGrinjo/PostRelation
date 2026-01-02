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
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Repository\Exception\InvalidMagicMethodCall;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @throws RuntimeException
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
                ? $operation->getStateOptions()->getEntityClass()
                : throw new RuntimeException('Processor: No entity class defined.')
            ;

            if (!is_string($entityClass) || !class_exists($entityClass)) {
                throw new RuntimeException('Processor: Not a valid EntityClass.');
            }

            return $this->updateOrCreate(
                entityClass: $entityClass,
                data: $data,
                operation: $operation,
                uriVariables: $uriVariables,
                context: $context,
            );
        }

        throw new RuntimeException('Cannot process this request.', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param class-string $entityClass
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     * @throws RuntimeException
     */
    private function updateOrCreate(
        string $entityClass,
        mixed $data,
        Operation $operation,
        array $uriVariables,
        array $context,
    ): ResponseDto {
        try {
            $entity = null;
            if ($operation instanceof Post) {
                $entity = new $entityClass;
            }

            if ($operation instanceof Put) {
                $repo = $this->entityManager->getRepository($entityClass);
                $entity = $repo->findOneBy($uriVariables);
            }

            ($data instanceof RequestDto && $entity instanceof EntityInterface)
                ? $this->objectMapper->map(
                    source: $data,
                    target: $entity,
                )
                : throw new RuntimeException(sprintf('Invalid request dto: %s', $entityClass))
            ;

            $this->persistProcessor->process(
                data: $entity,
                operation: $operation,
                uriVariables: $uriVariables,
                context: $context
            );

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
        } catch (InvalidMagicMethodCall|RuntimeException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode());
        }
    }
}
