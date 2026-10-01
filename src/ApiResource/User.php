<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\Filter\PartialSearchFilter;
use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\QueryParameter;
use App\Dto\User\Request\UserRequestDto;
use App\Dto\User\Response\UserCollectionResponseDto;
use App\Dto\User\Response\UserResponseDto;
use App\Entity\User as UserEntity;
use App\Processor\StandardProcessor;
use App\Validation\RegexValidations;

#[GetCollection(
    uriTemplate: 'users',
    shortName: 'user',
    output: UserCollectionResponseDto::class,
    stateOptions: new Options(entityClass: UserEntity::class),
    parameters: [
        'email' => new QueryParameter(filter: new PartialSearchFilter(), property: 'email'),
        'first_name' => new QueryParameter(filter: new PartialSearchFilter(), property: 'firstName'),
        'last_name' => new QueryParameter(filter: new PartialSearchFilter(), property: 'lastName'),
    ],
    itemUriTemplate: 'users/{uuid}',
)]
#[Get(
    uriTemplate: 'users/{uuid}',
    uriVariables: [
        'uuid' => new Link(fromClass: UserEntity::class, identifiers: ['uuid']),
    ],
    requirements: [
        'uuid' => RegexValidations::REGEX_UUID,
    ],
    shortName: 'user',
    output: UserResponseDto::class,
)]
#[Post(
    uriTemplate: 'users',
    shortName: 'user',
    input: UserRequestDto::class,
    output: UserResponseDto::class,
    processor: StandardProcessor::class,
    itemUriTemplate: 'users/{uuid}',
    map: false,
)]
//#[Put(
//    uriTemplate: self::ITEM_URI,
//    uriVariables: [
//        'uuid' => new Link(fromClass: UserEntity::class, identifiers: ['uuid']),
//    ],
//    requirements: [
//        'uuid' => RegexValidations::REGEX_UUID,
//    ],
//    input: UserRequestPutDto::class,
//    output: UserResponseDto::class,
//    processor: StandardProcessor::class,
//    map: false,
//)]
//#[Delete(
//    uriTemplate: 'users/{uuid}',
//    uriVariables: [
//        'uuid' => new Link(fromClass: UserEntity::class, identifiers: ['uuid']),
//    ],
//    requirements: [
//        'uuid' => RegexValidations::REGEX_UUID,
//    ],
//)]
final readonly class User {}
