<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/showAuthor/{name}', name: 'app_author_show')]
    public function showAuthor($name): Response
    {
        return $this->render('author/show.html.twig', [
            'name' => $name,
        ]);
    }

   
    #[Route('/list', name: 'app_author_list')]
    public function listAuthors(): Response
    {
        $authors = array(
            array('id' => 1, 'picture' => '/images/Victor.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200),
            array('id' => 3, 'picture' => '/images/Taha-hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
        );

        return $this->render('author/list.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/author/details/{id}', name: 'app_author_details')]
    public function authorDetails($id): Response
    {
        $authors = array(
            array('id' => 1, 'picture' => '/images/Victor.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200),
            array('id' => 3, 'picture' => '/images/Taha-hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
        );

        $author = null;
        foreach ($authors as $a) {
            if ($a['id'] == $id) {
                $author = $a;
                break;
            }
        }

        return $this->render('author/showAuthor.html.twig', [
            'author' => $author,
        ]);
    }

    #[Route('/author/add', name: 'app_author_add')]
    public function addAuthors(EntityManagerInterface $entityManager): Response
    {
        // Création des 3 auteurs
        $auth1 = new Author();
        $auth1->setUsername('Victor Hugo');
        $auth1->setEmail('victor.hugo@gmail.com');
        
        $auth2 = new Author();
        $auth2->setUsername('William Shakespeare');
        $auth2->setEmail('william.shakespeare@gmail.com');
        
        $auth3 = new Author();
        $auth3->setUsername('Taha Hussein');
        $auth3->setEmail('taha.hussein@gmail.com');
        
        // Persistance en base de données
        $entityManager->persist($auth1);
        $entityManager->persist($auth2);
        $entityManager->persist($auth3);
        $entityManager->flush();
        
        return new Response('Auteurs ajoutés avec succès !');
    }

    #[Route('/author/db-list', name: 'app_author_db_list')]
    public function listAuthorsFromDB(AuthorRepository $authorRepository): Response
    {
        // Récupération de tous les auteurs depuis la base de données
        $authors = $authorRepository->findAll();
        
        return $this->render('author/dbList.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/author/delete/{id}', name: 'app_author_delete')]
    public function deleteAuthor(int $id, AuthorRepository $authorRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupération de l'auteur depuis la base de données
        $author = $authorRepository->find($id);
        
        if (!$author) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }
        
        // Suppression de l'auteur
        $entityManager->remove($author);
        $entityManager->flush();
        
        // Redirection vers la liste des auteurs
        return $this->redirectToRoute('app_author_db_list');
    }

    #[Route('/author/edit/{id}', name: 'app_author_edit')]
    public function editAuthor(int $id, Request $request, AuthorRepository $authorRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupération de l'auteur depuis la base de données
        $author = $authorRepository->find($id);
        
        if (!$author) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }
        
        // Création du formulaire avec les données de l'auteur
        $form = $this->createForm(AuthorType::class, $author);
        
        // Traitement de la requête
        $form->handleRequest($request);
        
        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde en base de données
            $entityManager->flush();
            
            // Redirection vers la liste des auteurs
            return $this->redirectToRoute('app_author_db_list');
        }
        
        return $this->render('author/edit.html.twig', [
            'form' => $form->createView(),
            'author' => $author,
        ]);
    }


    #[Route('/author/new', name: 'app_author_new')]
    public function newAuthor(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création d'une nouvelle instance d'auteur
        $author = new Author();
        
        // Création du formulaire
        $form = $this->createForm(AuthorType::class, $author);
        
        // Traitement de la requête
        $form->handleRequest($request);
        
        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Persistance en base de données
            $entityManager->persist($author);
            $entityManager->flush();
            
            // Redirection vers la liste des auteurs
            return $this->redirectToRoute('app_author_db_list');
        }
        
        return $this->render('author/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
