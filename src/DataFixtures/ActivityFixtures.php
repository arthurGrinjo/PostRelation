<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\Trait\Numbers;
use App\Factory\ActivityFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ActivityFixtures extends Fixture implements DependentFixtureInterface
{
    use Numbers;

    public function load(ObjectManager $manager): void
    {
        ActivityFactory::createMany(self::ACTIVITIES * self::MULTIPLIER);
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
