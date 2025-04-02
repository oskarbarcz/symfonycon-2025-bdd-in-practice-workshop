<?php

declare(strict_types=1);

namespace App\Controller\User\Dto;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUserRequest
{
    #[OA\Property(type: 'string', example: 'john.doe@example.com')]
    #[Assert\NotBlank]
    public string $email;

    #[OA\Property(type: 'string', example: 'password123')]
    #[Assert\NotBlank]
    public string $password;
}