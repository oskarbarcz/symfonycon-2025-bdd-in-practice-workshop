<?php

declare(strict_types=1);

namespace App\Controller\Comment\Dto;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class CreateCommentRequest
{
    #[OA\Property(type: 'string', example: 'This is the content of my first post.')]
    #[Assert\NotBlank]
    public string $content;
}