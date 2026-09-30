<?php

declare(strict_types=1);

namespace App\ApiResource\User\Response;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\ApiResource\Interface\ResponseDto;
use App\Entity\User as UserEntity;
use App\ObjectMapper\Transform\UserResourceToEntity;
use App\Validation\RegexValidations;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[Get(
    uriTemplate: 'users/{uuid}',
    requirements: ['uuid' => RegexValidations::REGEX_UUID],
    shortName: 'user',
    stateOptions: new Options(entityClass: UserEntity::class),
)]
#[Map(target: UserEntity::class, transform: UserResourceToEntity::class)]
#[Map(source: UserEntity::class)]
final class UserResponseDto implements ResponseDto
{
    #[ApiProperty(writable: false, identifier: true)]
    public Uuid $uuid;

    #[Assert\Email]
    public string $email;

    #[Assert\Length(min: 2, max: 32)]
    public string $firstName;

    #[Assert\Length(min: 2, max: 32)]
    public string $lastName;
}
