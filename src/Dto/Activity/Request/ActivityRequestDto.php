<?php

declare(strict_types=1);

namespace App\Dto\Activity\Request;

use App\Dto\Interface\RequestDto;
use App\Entity\Activity;
use App\Entity\User;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Map does not help to resolve..
 */
// #[Map(target: Activity::class)]
class ActivityRequestDto implements RequestDto
{
    #[Assert\Length(min: 4, max: 128)]
    public string $name;

    #[Map(target: User::class)]
    public User $user;
}
