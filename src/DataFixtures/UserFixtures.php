<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\Trait\Numbers;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    use Numbers;

    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(self::USERS * self::MULTIPLIER);
    }
}
