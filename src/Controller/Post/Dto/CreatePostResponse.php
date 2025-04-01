<?php

declare(strict_types=1);

namespace App\Controller\Post\Dto;

use OpenApi\Attributes as OA;
use OpenApi\Attributes\Items;
use Symfony\Component\Validator\Constraints as Assert;

class CreatePostResponse extends CreatePostRequest
{
    #[OA\Property(type: 'string', format:  'uuid-v4', example: 'b2f1c3e4-5d6a-4b8e-9f0a-1b2c3d4e5f6g')]
    public string $id;
}