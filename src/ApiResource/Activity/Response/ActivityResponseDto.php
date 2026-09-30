<?php

declare(strict_types=1);

namespace App\ApiResource\Activity\Response;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\ApiResource\Interface\ResponseDto;
use App\ApiResource\User\Response\UserResponseDto;
use App\Entity\Activity as ActivityEntity;
use App\Validation\RegexValidations;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[Get(
    uriTemplate: 'activities/{uuid}',
    requirements: ['uuid' => RegexValidations::REGEX_UUID],
    shortName: 'activity',
    stateOptions: new Options(entityClass: ActivityEntity::class),
)]
#[Post(
    uriTemplate: 'activities',
    shortName: 'activity',
    stateOptions: new Options(entityClass: ActivityEntity::class),
)]
#[Delete(
    uriTemplate: 'activities/{uuid}',
    requirements: ['uuid' => RegexValidations::REGEX_UUID],
    shortName: 'activity',
    stateOptions: new Options(entityClass: ActivityEntity::class),
)]
#[Map(target: ActivityEntity::class, source: ActivityEntity::class)]
final class ActivityResponseDto implements ResponseDto
{
    #[ApiProperty(writable: false, identifier: true)]
    public Uuid $uuid;

    #[Assert\Length(min: 4, max: 128)]
    public string $name;

    #[Assert\NotNull]
    #[ApiProperty(readableLink: true)]
    public UserResponseDto $user;
}
