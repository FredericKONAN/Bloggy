<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function save(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAllPublishedOrderedQuery(?Tag $tag): Query
    {
        $queryBuilder =  $this->createQueryBuilder('p')
                ->addSelect('t')
                ->leftJoin('p.tags', 't')
//                ->andWhere( 'p.publishedAt IS NOT NULL')
//                ->andWhere( 'p.publishedAt <= :now')
                ->orderBy('p.publishedAt', 'DESC')
//                ->setParameter('now', new \DateTimeImmutable())
        ;


            if($tag){
                $queryBuilder->andWhere(':tag MEMBER OF p.tags')
                             ->setParameter('tag', $tag)
                ;
            }

               return $queryBuilder->getQuery();

//        $criteria = (new Criteria)
//            ->andWhere(Criteria::expr()->neq('publishedAt', null))
//            ->orderBy(['publishedAt'=> 'DESC'])
//        ;
//
//       return  $this->matching($criteria);

    }

    public function findOneByPublishedDateAnSlug(string $date, string $slug): ?Post
    {
        return $this->createQueryBuilder('p')
//            ->andWhere('p.publishedAt IS NOT NULL')
            ->andWhere('DATE(p.publishedAt) = :date')
            ->andWhere('p.slug = :slug')
            ->setParameters([
                'date' => $date,
                'slug' => $slug,
            ])
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findSimilar(Post $post, int $mxResult = 4): array
    {
        //Récupérer les articles
        // ayant des tags en commun
        //avec l'article passe en argument
        //ordonnée de l'article ayant plus de tag en commun
        // a l'article ayant le moin  de tag en commun.
        // dans le cas ou deux articles on le même nombre de tags
        // en commun, alors ils devront être ordonné du plus récent
        // au plus ancien
        // On retournera au maximum 4 articles
        //PS: pourquoi pas la valeur "4" devra etre customisable.

        return $this->createQueryBuilder('p')
            ->join('p.tags', 't')
            ->addSelect('COUNT(t.id) AS HIDDEN numberOfTag')
            ->andWhere('t IN (:tags)')
            ->andWhere('p != :post') // on exclut l'article courant
            ->setParameters([
                'tags' => $post->getTags(),
                'post' => $post
            ])
            ->groupBy('p.id')
            ->orderBy('numberOfTag', 'DESC')
            ->addOrderBy('p.publishedAt', 'DESC')
            ->setMaxResults($mxResult)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findMostCommented( int $mxResult): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.comments', 'c')
            ->addSelect('COUNT(c) AS HIDDEN numberOfComment')
            ->andWhere('c.isActive = true')
            ->groupBy('p')
            ->orderBy('numberOfComment', 'DESC')
            ->addOrderBy('p.publishedAt', 'DESC')
            ->setMaxResults($mxResult)
            ->getQuery()
            ->getResult()
            ;

    }

//    /**
//     * @return Post[] Returns an array of Post objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
