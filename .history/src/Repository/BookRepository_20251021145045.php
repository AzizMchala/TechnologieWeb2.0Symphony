 <?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * Compte le nombre total de livres - Version DQL
     * @return int
     */
    public function getNbrBooksDQL(): int
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT COUNT(b.id) FROM App\Entity\Book b'
        );
        
        return $query->getSingleScalarResult();
    }

    /**
     * Compte le nombre total de livres - Version QueryBuilder
     * @return int
     */
    public function getNbrBooksQueryBuilder(): int
    {
        return $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Récupère tous les livres d'un auteur - Version DQL
     * @param int $authorId
     * @return Book[]
     */
    public function getBooksByAuthorDQL(int $authorId): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT b FROM App\Entity\Book b
             WHERE b.author = :authorId
             ORDER BY b.publicationDate DESC'
        )->setParameter('authorId', $authorId);
        
        return $query->getResult();
    }

    /**
     * Récupère tous les livres d'un auteur - Version QueryBuilder
     * @param int $authorId
     * @return Book[]
     */
    public function getBooksByAuthorQueryBuilder(int $authorId): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.author = :authorId')
            ->setParameter('authorId', $authorId)
            ->orderBy('b.publicationDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
