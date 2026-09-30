<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Entity\Activity as ActivityEntity;
use App\Validation\RegexValidations;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[Get(
    uriTemplate: 'activities/{uuid}',
    shortName: 'activity',
    requirements: ['uuid' => RegexValidations::REGEX_UUID],
    stateOptions: new Options(entityClass: ActivityEntity::class),
)]
#[Post(
    uriTemplate: 'activities',
    shortName: 'activity',
    stateOptions: new Options(entityClass: ActivityEntity::class),
)]
#[Delete(
    uriTemplate: 'activities/{uuid}',
    shortName: 'activity',
    requirements: ['uuid' => RegexValidations::REGEX_UUID],
    stateOptions: new Options(entityClass: ActivityEntity::class),
)]
#[Map(source: ActivityEntity::class, target: ActivityEntity::class)]
final class Activity
{
    #[ApiProperty(writable: false, identifier: true)]
    public ?Uuid $uuid = null;

    #[Assert\Length(min: 4, max: 128)]
    public string $name;

    #[ApiProperty(readableLink: true)]
    #[Assert\NotNull]
    public User $user;
}
