<?php

namespace App\Twig\Runtime;

use App\Repository\PostRepository;
use Twig\Extension\RuntimeExtensionInterface;

class AppExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(private readonly PostRepository $postRepo){}

    public function totalPosts(): int
    {
        return $this->postRepo->count([]);
    }

    public function latestPosts($maxResult=5): array
    {
        return $this->postRepo->findBy([], ['publishedAt'=> 'DESC'], $maxResult);
    }

    public function mostCommentedPosts($maxResult): array
    {
        return $this->postRepo->findMostCommented($maxResult);
    }
}
