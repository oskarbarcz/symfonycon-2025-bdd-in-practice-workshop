<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\User\Dto\CreateUserRequest;
use App\Controller\User\Dto\CreateUserResponse;
use App\Entity\Comment;
use App\Entity\User;
use App\Repository\UserRepository;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\JsonContent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[OA\Tag(name: 'Users')]
final class UserController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly PasswordHasherInterface $passwordHasher,
    ) {}

    #[OA\RequestBody(content: new Model(type: CreateUserRequest::class))]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Creates user',
        content: new Model(type: CreateUserResponse::class)
    )]
    #[Route('/api/user', methods: ['POST'], format: 'json')]
    public function signUp(
        #[MapRequestPayload(acceptFormat: 'json')]
        CreateUserRequest $request
    ): Response {
        $newCommentId = Uuid::v4();

        $hashedPassword = $this->passwordHasher->hash($request->password);
        $user = new User($newCommentId, $request->email, $hashedPassword);
        $this->userRepository->save($user);

        return $this->json(CreateUserResponse::fromUser($user), Response::HTTP_CREATED);
    }

    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'User retrieved',
        content: new Model(type: CreateUserResponse::class)
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'User not found',
    )]
    #[Route('/api/user/{id}', methods: ['GET'], format: 'json')]
    public function get(Uuid $id): Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(CreateUserResponse::fromUser($user));
    }

    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Users retrieved',
        content: new JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: CreateUserResponse::class))
        )
    )]
    #[Route('/api/user', methods: ['GET'], format: 'json')]
    public function getAll(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->json(array_map(
            static fn (Comment $comment) => CreateUserResponse::fromUser($comment),
            $users
        ));
    }
}