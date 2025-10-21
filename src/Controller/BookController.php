<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('/', name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour le nombre de livres de l'auteur
            $author = $book->getAuthor();
            if ($author) {
                $author->setNbBooks($author->getNbBooks() + 1);
            }

            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', 'Le livre a été créé avec succès.');

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_show', methods: ['GET'])]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_book_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        $oldAuthor = $book->getAuthor();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newAuthor = $book->getAuthor();
            
            // Si l'auteur a changé, mettre à jour les compteurs
            if ($oldAuthor !== $newAuthor) {
                if ($oldAuthor) {
                    $oldAuthor->setNbBooks($oldAuthor->getNbBooks() - 1);
                }
                if ($newAuthor) {
                    $newAuthor->setNbBooks($newAuthor->getNbBooks() + 1);
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'Le livre a été modifié avec succès.');

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            // Mettre à jour le nombre de livres de l'auteur
            $author = $book->getAuthor();
            if ($author) {
                $author->setNbBooks($author->getNbBooks() - 1);
            }

            $entityManager->remove($book);
            $entityManager->flush();

            $this->addFlash('success', 'Le livre a été supprimé avec succès.');
        }

        return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/stats/count', name: 'app_book_count', methods: ['GET'])]
    public function getNbrBooks(BookRepository $bookRepository): Response
    {
        $countDQL = $bookRepository->getNbrBooksDQL();
        $countQB = $bookRepository->getNbrBooksQueryBuilder();

        return $this->render('book/stats.html.twig', [
            'count_dql' => $countDQL,
            'count_qb' => $countQB,
        ]);
    }

    #[Route('/author/{id}/books', name: 'app_book_by_author', methods: ['GET'])]
    public function getBooksByAuthor(int $id, BookRepository $bookRepository): Response
    {
        $booksDQL = $bookRepository->getBooksByAuthorDQL($id);
        $booksQB = $bookRepository->getBooksByAuthorQueryBuilder($id);

        return $this->render('book/by_author.html.twig', [
            'books_dql' => $booksDQL,
            'books_qb' => $booksQB,
            'author_id' => $id,
        ]);
    }
}
