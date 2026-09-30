<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Activity as ActivityEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Uid\Uuid;

#[GetCollection(
    uriTemplate: 'activities',
    shortName: 'activity',
    itemUriTemplate: 'activities/{uuid}',
    stateOptions: new Options(entityClass: ActivityEntity::class),
)]
#[Map(source: ActivityEntity::class)]
final class ActivityListItem
{
    #[ApiProperty(identifier: true)]
    public Uuid $uuid;

    public string $name;

    #[ApiProperty(readableLink: false)]
    public User $user;
}
