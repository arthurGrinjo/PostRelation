<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Dto\Activity\Request\ActivityRequestDto;
use App\Dto\Activity\Response\ActivityCollectionResponseDto;
use App\Dto\Activity\Response\ActivityResponseDto;
use App\Entity\Activity as ActivityEntity;
use App\Processor\StandardProcessor;
use App\Validation\RegexValidations;

#[ApiResource(
    shortName: 'activity',
    stateOptions: new Options(entityClass: ActivityEntity::class),
)]
#[GetCollection(
    uriTemplate: 'activities',
    output: ActivityCollectionResponseDto::class,
)]
#[Get(
    uriTemplate: 'activities/{uuid}',
    uriVariables: [
        'uuid' => new Link(fromClass: ActivityEntity::class, identifiers: ['uuid']),
    ],
    requirements: [
        'uuid' => RegexValidations::REGEX_UUID,
    ],
    output: ActivityResponseDto::class,
)]
#[Post(
    uriTemplate: 'activities',
    input: ActivityRequestDto::class,
    output: ActivityResponseDto::class,
    processor: StandardProcessor::class,
    map: false,
)]
#[Delete(
    uriTemplate: 'activities/{uuid}',
    uriVariables: [
        'uuid' => new Link(fromClass: ActivityEntity::class, identifiers: ['uuid']),
    ],
    requirements: [
        'uuid' => RegexValidations::REGEX_UUID,
    ],
)]
final readonly class Activity {}
