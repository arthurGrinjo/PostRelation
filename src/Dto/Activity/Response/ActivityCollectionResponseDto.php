<?php

declare(strict_types=1);

namespace App\Dto\Activity\Response;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Dto\Interface\ResponseDto;
use App\Dto\User\Response\UserResponseDto;
use App\Entity\Activity as ActivityEntity;
use App\Entity\User;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[Map(source: ActivityEntity::class)]
final class ActivityCollectionResponseDto implements ResponseDto
{
    #[ApiProperty(writable: false, identifier: true)]
    public Uuid $uuid;

    #[Assert\NotBlank]
    public string $name;

//    #[ApiProperty(readableLink: false)]
//    public UserResponseDto $user;
}
