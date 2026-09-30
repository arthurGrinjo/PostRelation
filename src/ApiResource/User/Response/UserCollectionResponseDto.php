<?php

declare(strict_types=1);

namespace App\ApiResource\User\Response;

use ApiPlatform\Doctrine\Orm\Filter\PartialSearchFilter;
use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\QueryParameter;
use App\ApiResource\Interface\ResponseDto;
use App\Entity\User as UserEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[GetCollection(
    uriTemplate: 'users',
    shortName: 'user',
    stateOptions: new Options(entityClass: UserEntity::class),
    parameters: [
        'email' => new QueryParameter(filter: new PartialSearchFilter(), property: 'email'),
        'first_name' => new QueryParameter(filter: new PartialSearchFilter(), property: 'firstName'),
        'last_name' => new QueryParameter(filter: new PartialSearchFilter(), property: 'lastName'),
    ],
    itemUriTemplate: 'users/{uuid}',
)]
#[Map(source: UserEntity::class)]
final class UserCollectionResponseDto implements ResponseDto
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
