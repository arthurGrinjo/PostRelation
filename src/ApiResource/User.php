<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use App\Entity\User as UserEntity;
use App\ObjectMapper\Transform\UserResourceToEntity;
use App\Validation\RegexValidations;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Uid\Uuid;

#[Get(
    uriTemplate: 'users/{uuid}',
    shortName: 'user',
    requirements: ['uuid' => RegexValidations::REGEX_UUID],
    stateOptions: new Options(entityClass: UserEntity::class),
)]
#[Map(target: UserEntity::class, transform: UserResourceToEntity::class)]
// Symfony ObjectMapper >= 8.1 resolves the nested entity -> resource mapping from this source map (reverse class-map), no #[Map] needed on the entity
#[Map(source: UserEntity::class)]
final class User
{
    #[ApiProperty(identifier: true)]
    public Uuid $uuid;

    public string $email;

    public string $firstName;

    public string $lastName;
}
