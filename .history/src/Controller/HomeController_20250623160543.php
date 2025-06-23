<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/' ,'pages/home.index', methods:['GET'] )]
    public function index(): Response
    {
// Correct
return $this->render('pages/ingredient/index.html.twig', [
    // ...
]);
            'controller_name' => 'HomeController',
        ]);
    }
}