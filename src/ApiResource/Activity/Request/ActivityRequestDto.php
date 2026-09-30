<?php

declare(strict_types=1);

namespace App\ApiResource\Activity\Request;

use App\Entity\Activity;
use App\Entity\User;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

#[Map(target: Activity::class)]
class ActivityRequestDto
{
    #[Assert\Length(min: 3, max: 128)]
    public string $name;

    #[Assert\NotNull]
    #[Map(target: 'user')]
    public ?User $user;

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
