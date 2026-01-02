<?php

namespace App\Processor;

use ApiPlatform\Validator\Exception\ValidationException;
use App\Dto\Interface\RequestDto;
use App\Dto\Interface\ResponseDto;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class Validator
{
    public function __construct(
        private ValidatorInterface $validator,
    ) {}

    /**
     * @return array{string}|array{}
     * @throws ValidationException
     */
    public function validateDto(RequestDto|ResponseDto $object, bool $breakOnError = false): array
    {
        $validationErrors = $this->validator->validate($object);
        $errorMessages = [];

        foreach ($validationErrors as $error) {
            $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
        }

        if ($breakOnError && count($errorMessages) > 0) {
            throw new ValidationException(
                message: 'Some fields are incorrect.',
            );
        }

        return $errorMessages;
    }
}
