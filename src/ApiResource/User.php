<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\Filter\PartialSearchFilter;
use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\QueryParameter;
use App\Dto\User\Request\UserRequestDto;
use App\Dto\User\Response\UserCollectionResponseDto;
use App\Dto\User\Response\UserResponseDto;
use App\Entity\User as UserEntity;
use App\Processor\StandardProcessor;
use App\Validation\RegexValidations;

#[GetCollection(
    uriTemplate: 'users',
    shortName: self::SHORT_NAME,
    output: UserCollectionResponseDto::class,
    stateOptions: new Options(entityClass: UserEntity::class),
    parameters: [
        'email' => new QueryParameter(filter: new PartialSearchFilter(), property: 'email'),
        'first_name' => new QueryParameter(filter: new PartialSearchFilter(), property: 'firstName'),
        'last_name' => new QueryParameter(filter: new PartialSearchFilter(), property: 'lastName'),
    ],
)]
#[Get(
    uriTemplate: 'users/{uuid}',
    uriVariables: [
        'uuid' => new Link(fromClass: UserEntity::class, identifiers: ['uuid']),
    ],
    requirements: [
        'uuid' => RegexValidations::REGEX_UUID,
    ],
    shortName: self::SHORT_NAME,
    output: UserResponseDto::class,
    stateOptions: new Options(entityClass: UserEntity::class),
)]
#[Post(
    uriTemplate: 'users',
    shortName: self::SHORT_NAME,
    input: UserRequestDto::class,
    output: UserResponseDto::class,
    processor: StandardProcessor::class,
    stateOptions: new Options(entityClass: UserEntity::class),
    map: false,
)]
#[Put(
    uriTemplate: 'users/{uuid}',
    uriVariables: [
        'uuid' => new Link(fromClass: UserEntity::class, identifiers: ['uuid']),
    ],
    requirements: [
        'uuid' => RegexValidations::REGEX_UUID,
    ],
    shortName: self::SHORT_NAME,
    input: UserResponseDto::class,
    output: UserResponseDto::class,
    processor: StandardProcessor::class,
    stateOptions: new Options(entityClass: UserEntity::class),
    map: false,
)]
#[Delete(
    uriTemplate: 'users/{uuid}',
    uriVariables: [
        'uuid' => new Link(fromClass: UserEntity::class, identifiers: ['uuid']),
    ],
    requirements: [
        'uuid' => RegexValidations::REGEX_UUID,
    ],
    shortName: self::SHORT_NAME,
)]
final readonly class User {
    final public const string SHORT_NAME = 'user';
}
