<?php

declare(strict_types=1);

namespace App\Controller\Comment\Dto;

use App\Entity\Comment;
use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;

class CreateCommentResponse extends CreateCommentRequest
{
    #[OA\Property(type: 'string', format:  'uuid-v4', example: 'b2f1c3e4-5d6a-4b8e-9f0a-1b2c3d4e5f6g')]
    public Uuid $id;

    public static function fromComment(Comment $comment): self
    {
        $self = new self();

        $self->id = $comment->getId();
        $self->content = $comment->getContent();

        return $self;

    }
}