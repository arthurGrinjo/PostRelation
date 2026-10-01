<?php

declare(strict_types=1);

namespace App\Dto\User\Response;

use ApiPlatform\Metadata\ApiProperty;
use App\Dto\Interface\ResponseDto;
use App\Entity\User as UserEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[Map(source: UserEntity::class)]
final readonly class UserCollectionResponseDto implements ResponseDto
{
    public function __construct(
        #[ApiProperty(readable: false, identifier: true)]
        public Uuid $uuid,

        #[Assert\NotBlank]
        public string $email,

        #[Assert\NotBlank]
        public string $firstName,

        #[Assert\NotBlank]
        public string $lastName,
    ) {}
}
