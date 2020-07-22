<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
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

    /**
     * @return Post[] Returns an array of Post objects associated with given tag
     */
    public function findByTag(Tag $tag)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.tags', 't')
            ->andWhere('t.id = :id')
            ->setParameter('id', $tag->getId())
            ->orderBy('p.title', 'DESC')
        ;
    }

    /**
     * @return Post[] Returns an array of Post objects associated with given tag
     */
    public function findByTagDQL(Tag $tag)
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Post p
            INNER JOIN p.tags t
            WHERE t.id = :id
            ORDER BY p.title ASC'
        )->setParameter('id', $tag->getId());
    }

    /**
     * @return Post[] Returns an array of Post objects associated with given categories
     */
    public function findByCategory(Category $category)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $category->getId())
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Post[] Returns an array of Post objects associated with given category
    //  */
    // public function findByCategoryDQL(Category $category)
    // {
    //     $entityManager = $this->getEntityManager();

    //     $query = $entityManager->createQuery(
    //         'SELECT p
    //         FROM App\Entity\Post p
    //         INNER JOIN p.category c
    //         WHERE c.id = :id
    //         ORDER BY p.title ASC'
    //     )->setParameter('id', $category->getId());

    //     // returns an array of Post objects
    //     return $query->getResult();
    // }
    

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
