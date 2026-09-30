<?php

declare(strict_types=1);

namespace App\ApiResource\User\Request;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Post;
use App\ApiResource\Interface\RequestDto;
use App\Entity\User as UserEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[Post(
    uriTemplate: 'users',
    shortName: 'user',
    stateOptions: new Options(entityClass: UserEntity::class),
    itemUriTemplate: 'users/{uuid}',
)]
#[Map(target: UserEntity::class, source: UserEntity::class)]
final class UserRequestDto implements RequestDto
{
    #[ApiProperty(writable: false, identifier: true)]
    public ?Uuid $uuid = null;

    #[Assert\Email]
    public string $email;

    #[ApiProperty(readable: false)]
    #[Assert\Length(min: 8, max: 32)]
    public string $password;

    #[Assert\Length(min: 0, max: 60)]
    public string $firstName;

    #[Assert\Length(min: 0, max: 60)]
    public string $lastName;
}
