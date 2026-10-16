<?php

declare(strict_types=1);

namespace App\Dto\Activity\Request;

use App\Dto\Interface\RequestDto;
use App\Entity\Activity;
use App\Entity\User;
use App\ObjectMapper\Transform\IriToEntity;
use App\Validation\RegexValidations;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

#[Map(target: Activity::class, source: Activity::class)]
class ActivityRequestTestDto implements RequestDto
{
    #[Assert\Length(min: 4, max: 128)]
    public string $name;

    #[Map(target: 'user', transform: IriToEntity::class)]
    #[Assert\Regex(RegexValidations::IRI)]
    public string $userIri;

    public User $user;
}
