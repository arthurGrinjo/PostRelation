<?php

declare(strict_types=1);

namespace App\Dto\User\Response;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Dto\Interface\ResponseDto;
use App\Entity\User as UserEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'user',
    operations: [],
)]
#[Map(source: UserEntity::class)]
class UserResponseDto implements ResponseDto
{
    public function __construct(
        #[ApiProperty(readable: false, identifier: true)]
        #[Assert\NotBlank]
        public Uuid $uuid,

        #[Assert\NotBlank]
        public string $email,

        #[SerializedName('first_name'), Assert\NotBlank]
        public string $firstName,

        #[SerializedName('last_name'), Assert\NotBlank]
        public string $lastName,
    ) {}
}
