<?php

declare(strict_types=1);

namespace App\Controller\Comment;

use App\Controller\Comment\Dto\CreateCommentRequest;
use App\Controller\Comment\Dto\CreateCommentResponse;
use App\Controller\Post\Dto\CreatePostResponse;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\JsonContent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[OA\Tag(name: 'Post comments')]
final class CommentController extends AbstractController
{
    public function __construct(
        private readonly CommentRepository $commentRepository,
        private readonly PostRepository $postRepository,
    ) {}

    #[OA\RequestBody(content: new Model(type: CreateCommentRequest::class))]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Creates comment',
        content: new Model(type: CreatePostResponse::class)
    )]
    #[Route('/api/post/{postId}/comment', methods: ['POST'], format: 'json')]
    public function create(
        Uuid $postId,
        #[MapRequestPayload(acceptFormat: 'json')]
        CreateCommentRequest $request
    ): Response {
        $post = $this->postRepository->find($postId);

        if (!$post) {
            return $this->json(['error' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }

        $newCommentId = Uuid::v4();
        $comment = new Comment($newCommentId, $request->content, $post);
        $post->addComment($comment);

        $this->postRepository->save($post);

        return $this->json(CreateCommentResponse::fromComment($comment), Response::HTTP_CREATED);
    }

    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Comment retrieved',
        content: new Model(type: CreateCommentResponse::class)
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Comment not found',
    )]
    #[Route('/api/post/{postId}/comment/{commentId}', methods: ['GET'], format: 'json')]
    public function get(Uuid $commentId): Response
    {
        $comment = $this->commentRepository->find($commentId);

        if (!$comment) {
            return $this->json(['error' => 'Comment not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(CreateCommentResponse::fromComment($comment));
    }

    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Users retrieved',
        content: new JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: CreatePostResponse::class))
        )
    )]
    #[Route('/api/post/{postId}/comment', methods: ['GET'], format: 'json')]
    public function getAllForPost(Uuid $postId): Response
    {
        $comments = $this->commentRepository->findBy(['post' => $postId]);

        return $this->json(array_map(
            static fn (Comment $comment) => CreateCommentResponse::fromComment($comment),
            $comments
        ));
    }
}