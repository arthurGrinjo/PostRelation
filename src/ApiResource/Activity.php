<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
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

#[GetCollection(
    uriTemplate: 'activities',
    shortName: self::SHORT_NAME,
    output: ActivityCollectionResponseDto::class,
    stateOptions: new Options(entityClass: ActivityEntity::class),
)]
#[Get(
    uriTemplate: 'activities/{uuid}',
    uriVariables: [
        'uuid' => new Link(fromClass: ActivityEntity::class, identifiers: ['uuid']),
    ],
    requirements: [
        'uuid' => RegexValidations::REGEX_UUID,
    ],
    shortName: self::SHORT_NAME,
    output: ActivityResponseDto::class,
    stateOptions: new Options(entityClass: ActivityEntity::class),
)]
#[Post(
    uriTemplate: 'activities',
    shortName: self::SHORT_NAME,
    input: ActivityRequestDto::class,
    output: ActivityResponseDto::class,
    processor: StandardProcessor::class,
    stateOptions: new Options(entityClass: ActivityEntity::class),
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
    shortName: self::SHORT_NAME,
)]
final readonly class Activity {
    final public const string SHORT_NAME = 'activity';
}
