<?php

declare(strict_types=1);

namespace App\Entity\Enum;

use App\Entity\User;

enum UriCatalog: string
{
    case USERS = User::class;
}
