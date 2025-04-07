<?php

declare(strict_types=1);

namespace App\Controller\Post\Dto;

use App\Entity\Post;
use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;

class CreatePostResponse extends CreatePostRequest
{
    #[OA\Property(type: 'string', format:  'uuid-v4', example: 'b2f1c3e4-5d6a-4b8e-9f0a-1b2c3d4e5f6g')]
    public Uuid $id;

    #[OA\Property(type: 'string', example: '2023-10-01T00:00:00.000Z')]
    public \DateTimeImmutable $createdAt;

    public static function fromPost(Post $post): static
    {
        $self = new static();

        $self->id = $post->getId();
        $self->title = $post->getTitle();
        $self->content = $post->getContent();
        $self->tags =$post->getTags();
        $self->createdAt = $post->getCreatedAt();

        return $self;
    }
}