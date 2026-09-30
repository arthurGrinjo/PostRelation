<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\Filter\PartialSearchFilter;
use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\QueryParameter;
use App\Entity\User as UserEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Uid\Uuid;

#[GetCollection(
    uriTemplate: 'users',
    shortName: 'user',
    itemUriTemplate: 'users/{uuid}',
    stateOptions: new Options(entityClass: UserEntity::class),
    parameters: [
        'email' => new QueryParameter(filter: new PartialSearchFilter(), property: 'email'),
        'first_name' => new QueryParameter(filter: new PartialSearchFilter(), property: 'firstName'),
        'last_name' => new QueryParameter(filter: new PartialSearchFilter(), property: 'lastName'),
    ],
)]
#[Map(source: UserEntity::class)]
final class UserListItem
{
    #[ApiProperty(identifier: true)]
    public Uuid $uuid;

    public string $email;

    public string $firstName;

    public string $lastName;
}
