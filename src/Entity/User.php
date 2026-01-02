<?php

declare(strict_types=1);

namespace App\Entity;

use App\Dto\User\Response\UserResponseDto;
use App\Entity\Enum\UserRole;
use App\Entity\Trait\IdentifiableEntity;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: UserRepository::class)]
#[Map(target: UserResponseDto::class)]
class User implements EntityInterface, PasswordAuthenticatedUserInterface
{
    use IdentifiableEntity;

    #[Column(type: Types::STRING, length: 180, unique: true, nullable: false)]
    private string $email;

    #[Column(type: Types::STRING, length: 64, nullable: false)]
    private string $password;

    #[Column(type: Types::STRING, length: 60, nullable: true)]
    private string $firstName = '';

    #[Column(type: Types::STRING, length: 60, nullable: true)]
    private string $lastName = '';

    /**
     * @var array<int,string>
     */
    #[Column(type: Types::SIMPLE_ARRAY)]
    private array $roles = [UserRole::ROLE_USER->value];

    public function __construct() {
        $this->uuid = Uuid::v6();
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param non-empty-string $email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     *
     * @return array|string[]
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    /**
     * @param array<int,string> $roles
     */
    public function setRoles(array $roles): self
    {
        $roles[] = UserRole::ROLE_USER->value; //at least
        $this->roles = array_values($roles);
        return $this;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles(), true);
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
