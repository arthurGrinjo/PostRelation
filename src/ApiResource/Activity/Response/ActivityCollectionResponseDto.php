<?php

declare(strict_types=1);

namespace App\ApiResource\Activity\Response;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\GetCollection;
use App\ApiResource\Interface\ResponseDto;
use App\ApiResource\User\Response\UserResponseDto;
use App\Entity\Activity as ActivityEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[GetCollection(
    uriTemplate: 'activities',
    shortName: 'activity',
    stateOptions: new Options(entityClass: ActivityEntity::class),
    itemUriTemplate: 'activities/{uuid}',
)]
#[Map(source: ActivityEntity::class)]
final class ActivityCollectionResponseDto implements ResponseDto
{
    #[ApiProperty(writable: false, identifier: true)]
    public Uuid $uuid;

    #[Assert\Length(min: 4, max: 128)]
    public string $name;

    #[Assert\NotNull]
    #[ApiProperty(readableLink: false)]
    public UserResponseDto $user;
}
