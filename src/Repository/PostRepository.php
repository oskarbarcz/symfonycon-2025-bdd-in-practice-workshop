<?php

declare(strict_types=1);

namespace App\Repository;

use App\Controller\Post\Dto\CreatePostRequest;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Post>
 */
final class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function add(Uuid $id, CreatePostRequest $request): Post
    {
        $entityManager = $this->getEntityManager();

        $post = new Post(
            $id,
            $request->title,
            $request->content,
            $request->tags
        );

        $entityManager->persist($post);
        $entityManager->flush();

        return $post;
    }
}
