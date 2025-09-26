<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route(path: '/service')]
final class ServiceController extends AbstractController
{
    #[Route('/serv', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }

    #[Route('/get/{name}', name: 'getservice')]
    public function showService ($name): Response
    {
        return $this->render('service/showService.html.twig', [
            'name' => $name,
        ]);
    }

    #[Route('/goto-index', name: 'goto_index')]
    public function goToIndex(): Response
    {
        return $this->redirectToRoute('app_service');
    }
}
