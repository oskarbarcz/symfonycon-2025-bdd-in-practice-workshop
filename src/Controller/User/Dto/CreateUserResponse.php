<?php

declare(strict_types=1);

namespace App\Controller\User\Dto;

use App\Entity\User;
use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;

class CreateUserResponse
{
    #[OA\Property(type: 'string', format:  'uuid-v4', example: 'b2f1c3e4-5d6a-4b8e-9f0a-1b2c3d4e5f6g')]
    public Uuid $id;

    #[OA\Property(type: 'string', example: 'john.doe@example.com')]
    public string $email;

    public static function fromUser(User $user): self
    {
        $self = new self();

        $self->id = $user->getId();
        $self->email = $user->getEmail();

        return $self;

    }
}