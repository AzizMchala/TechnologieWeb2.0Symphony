<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/student')]
final class StudentController extends AbstractController
{
    #[Route('/app', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'name' => '3a234StudentController',
        ]);
    }


     #[Route('/add', name: 'app_student_add')]
    public function addStudent(): Response
    {
        return new Response(
            "Student Added Successfully!"
        );
    }


    #[Route('/get/{name}', name: 'gets')]
    public function getStudent($name): Response
    {
        return new Response(
            "Student Added Successfully!".$name
        );
    }

    #[Route('/test', name: 'app_student_test')]
    public function test(): Response
    {
        $moyen = 15;
        $tab=['a','b','c'];
        return $this->render('student/index.html.twig', [
            'moy' => $moyen,
            'tab' => $tab,
        ]);
    }   
}
