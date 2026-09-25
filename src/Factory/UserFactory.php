<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<User>
 */
final class UserFactory extends PersistentObjectFactory
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();

        $this->passwordHasher = $passwordHasher;
    }

    public static function class(): string
    {
        return User::class;
    }

    /**
     * @return array<string,mixed>
     */
    protected function defaults(): array
    {
        return [
            'email' => self::faker()->email(),
            'password' => 'test123!',
            'roles' => [],
            'first_name' => self::faker()->firstName(),
            'last_name' => self::faker()->lastName(),
        ];
    }

    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function(User $user) {
                $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
            })
            ;
    }
}
