<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new Utilisateur;

        $user->setNom('Super codeur');
        $user->setEmail('supercodeur@gmail.com');
        $user->setPassword('$2y$13$AIO5fpMV9ka1rBJ.tjX2WONrfUAYpZgN5OkvdZ6oJtrHKozCVEUmC');
        $manager->persist($user);

        $admin = new Utilisateur;

        $admin->setNom('Mister Admin');
        $admin->setEmail('admin@bloggy.wip');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword('$2y$13$enlzkwp4W.TuuYWH5DLO8eHmYOUl4yPyheiSOa6eLLE5n8GLOwa26');
        $manager->persist($admin);

        // create 10 articles! Bam!
        for ($i = 1; $i <= 10; $i++) {
            $post = new Post;
            $post->setTitre('Article'.$i);
            $post->setSlug('article-'.$i);
            $post->setContenu('Once your fixtures have been written, load them by executing this command');
            $post->setPublishedAt(
                mt_rand(1,10) >=5
                ? new \DateTimeImmutable(sprintf('-%d days', mt_rand(10, 50) )): null
                );
            $post->setAuthor(mt_rand(1,10) >=5 ? $user:$admin);
            $manager->persist($post);
        }

        $manager->flush();

    }
}
