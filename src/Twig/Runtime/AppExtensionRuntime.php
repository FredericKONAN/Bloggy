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

//    public function latestPosts(int $maxResult=5): array
//    {
//        return $this->postRepo->findBy([], ['publishedAt'=> 'DESC'], $maxResult);
//    }

    public function mostCommentedPosts(int $maxResult = 5): array
    {
        return $this->postRepo->findMostCommented($maxResult);
    }

    public function shasum256($val): string
    {
        return hash('sha256',$val);
    }

    public function pluralize(int $quantity, string $singular, ?string $plural= null): string
    {

        $plural ??= $singular . 's';

        $singularOrPlural = $quantity === 1 ? $singular : $plural;

        return " $quantity $singularOrPlural";
    }

}
