<?php

namespace App\Controller;

use App\Repository\IngredientRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController {
    #[ Route( '/ingredient', name: 'app_ingredient' ) ]

    public function index( IngredientRepository $ingredientRepository, PaginatorInterface $paginator, Request $request ): Response {
        $ingredients = $ingredientRepository->findAll();
        £I
        return $this->render( 'pages/ingredient/index.html.twig', [
            'controller_name' => 'IngredientController',
            'ingredients' => $ingredients,
        ] );
    }
}

