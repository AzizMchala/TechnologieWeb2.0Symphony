<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
    // Get number of books (DQL)
    public function getNbrBooksDQL(): int
    {
        $dql = 'SELECT COUNT(b.id) FROM App\\Entity\\Book b';
        $query = $this->getEntityManager()->createQuery($dql);
        return (int) $query->getSingleScalarResult();
    }

    // Get number of books (QueryBuilder)
    public function getNbrBooksQB(): int
    {
        return (int) $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Get books by author (DQL)
    public function getBooksByAuthorDQL($authorId): array
    {
        $dql = 'SELECT b FROM App\\Entity\\Book b WHERE b.author = :authorId';
        $query = $this->getEntityManager()->createQuery($dql)
            ->setParameter('authorId', $authorId);
        return $query->getResult();
    }

    // Get books by author (QueryBuilder)
    public function getBooksByAuthorQB($authorId): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.author = :authorId')
            ->setParameter('authorId', $authorId)
            ->getQuery()
            ->getResult();
    }
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
