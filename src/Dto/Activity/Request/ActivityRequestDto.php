<?php

declare(strict_types=1);

namespace App\Dto\Activity\Request;

use App\Dto\Interface\RequestDto;
use App\Validation\RegexValidations;
use Symfony\Component\Validator\Constraints as Assert;

class ActivityRequestDto implements RequestDto
{
    #[Assert\Length(min: 4, max: 128)]
    public string $name;

     #[Assert\Regex(RegexValidations::IRI)]
    public string $user;
}
