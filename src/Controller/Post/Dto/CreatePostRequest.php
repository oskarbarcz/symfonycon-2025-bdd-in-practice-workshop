<?php

declare(strict_types=1);

namespace App\Controller\Post\Dto;

use OpenApi\Attributes as OA;
use OpenApi\Attributes\Items;
use Symfony\Component\Validator\Constraints as Assert;

class CreatePostRequest
{
    #[OA\Property(type: 'string', example: 'My First Post')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public string $title;

    #[OA\Property(type: 'string', example: 'This is the content of my first post.')]
    #[Assert\NotBlank]
    public string $content;

    #[OA\Property(type: 'array', items: new Items(type: 'string'), example: ['php', 'symfony', 'api'])]
    public array $tags;
}