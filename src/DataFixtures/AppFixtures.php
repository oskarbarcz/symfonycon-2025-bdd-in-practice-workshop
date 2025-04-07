<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $post1 = new Post(
            id: Uuid::fromString('f72dc06d-a50b-4f87-9ac6-303fe18e7270'),
            title: 'My First Post',
            content: 'This is the content of my first post.',
            tags: ['php', 'symfony', 'api'],
            createdAt: new \DateTimeImmutable('2025-01-01T00:00:00+00:00'),
        );

        $manager->persist($post1);
        $manager->flush();
    }
}
