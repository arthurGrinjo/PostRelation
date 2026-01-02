<?php

declare(strict_types=1);

namespace App\Dto\Activity\Response;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Dto\Interface\ResponseDto;
use App\Dto\User\Response\UserResponseDto;
use App\Entity\Activity as ActivityEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'activity',
    operations: [],
)]
#[Map(source: ActivityEntity::class)]
final readonly class ActivityCollectionResponseDto implements ResponseDto
{
    public function __construct(
        #[ApiProperty(readable: false, identifier: true)]
        #[Assert\NotBlank]
        public Uuid $uuid,

        #[Assert\NotBlank]
        public string $name,

        #[Assert\NotBlank]
        #[ApiProperty(readableLink: false)]
        public UserResponseDto $user,
    ) {}
}
