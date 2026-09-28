<?php

declare(strict_types=1);

namespace App\Resources\Activity\Request;

use App\Resources\Interface\RequestDto;
use App\Entity\Activity;
use App\Entity\User;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(target: Activity::class)]
class ActivityTestRequestDto implements RequestDto
{
    public string $name = 'testje';

    #[Map(target: 'user')]
    public ?User $user;

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
