<?php

declare(strict_types=1);

namespace App\Controller\Post;

use App\Controller\Post\Dto\CreatePostRequest;
use App\Controller\Post\Dto\CreatePostResponse;
use App\Repository\PostRepository;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\JsonContent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[OA\Tag(name: 'Posts')]
final class PostController extends AbstractController
{
    public function __construct(private readonly PostRepository $repository) {}

    #[OA\RequestBody(content: new Model(type: CreatePostRequest::class))]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Creates user',
        content: new Model(type: CreatePostResponse::class)
    )]
    #[Route('/api/post', methods: ['POST'], format: 'json')]
    public function create(
        #[MapRequestPayload(acceptFormat: 'json')]
        CreatePostRequest $request
    ): Response {
        $newPostId = Uuid::v4();

        $createdPost = $this->repository->add($newPostId, $request);

        return $this->json($createdPost, Response::HTTP_CREATED);
    }

    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'User retrieved',
        content: new Model(type: CreatePostResponse::class)
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'User not found',
    )]
    #[Route('/api/post/{id}', methods: ['GET'], format: 'json')]
    public function get(Uuid $id): Response
    {
        $post = $this->repository->find($id);

        if (!$post) {
            return $this->json(['error' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($post);
    }

    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Users retrieved',
        content: new JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: CreatePostResponse::class))
        )
    )]
    #[Route('/api/post', methods: ['GET'], format: 'json')]
    public function getAll(): Response
    {
        $posts = $this->repository->findAll();

        return $this->json($posts);
    }
}