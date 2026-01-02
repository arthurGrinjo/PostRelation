<?php

declare(strict_types=1);

namespace App\Entity\Trait;

use ApiPlatform\Metadata\ApiProperty;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Uid\Uuid;

trait IdentifiableEntity
{
    #[Id, GeneratedValue]
    #[Column(type: 'integer', unique: true)]
    #[ApiProperty(readable: false, writable: false, identifier: false)]
    private ?int $id;

    #[Column(type: 'uuid', unique: true)]
    #[ApiProperty(readable: false, writable: false, identifier: true)]
    private Uuid $uuid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }
}

