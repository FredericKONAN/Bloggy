<?php

namespace App\DataFixtures;

use App\Entity\Comments;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passWOrdHasher, private SluggerInterface $slug){}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");

        $user = new Utilisateur;

        $user->setNom('Super codeur');
        $user->setEmail('supercodeur@gmail.com');
        $user->setPassword($this->passWOrdHasher->hashPassword($user, 'secret'));
        $manager->persist($user);

        $admin = new Utilisateur;

        $admin->setNom('Mister Admin');
        $admin->setEmail('admin@bloggy.wip');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passWOrdHasher->hashPassword($admin, 'secret'));
        $manager->persist($admin);

        //On ajout les tags dans un tableau a ce niveau
        $tags = [];
        for ($j = 1; $j <= 10; $j++) {
            $tag = new Tag();
            $tag->setName($faker->unique()->word());
            $manager->persist($tag);
            $tags [] = $tag;
        }

        // create 10 articles! Bam!
        for ($i = 1; $i <= 10; $i++) {
            $post = new Post;
            $post->setTitre( $faker->unique()->sentence(4));
//            $post->setSlug($this->slug->slug(mb_strtolower($titre)));
            $post->setContenu($faker->paragraph(10));
            $post->setPublishedAt(
                $faker->boolean(50)
                ?  \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-50 days', '-10 days'))
                    : null
                );
            $post->setAuthor($faker->boolean(50) ? $user:$admin);

            foreach($faker->randomElements($tags, 3) as $tag) {
                $post->addTag($tag);
            }

            $manager->persist($post);

            for ($k = 1; $k <= $faker->numberBetween(1,5); $k++) {
                $comment = new Comments;

                $comment->setName($faker->name());
                $comment->setEmail($faker->email());
                $comment->setContenu($faker->paragraph());
                $comment->setIsActive($faker->boolean(90));
                $comment->setPost($post);
                $manager->persist($comment);
            }
        }


        $manager->flush();

    }
}
