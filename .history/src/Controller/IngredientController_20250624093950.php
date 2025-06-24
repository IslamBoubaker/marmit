<?php

namespace App\Controller;

use App\Repository\IngredientRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class IngredientController extends AbstractController {
/**
 * Undoc
 */




    #[ Route( '/ingredient', name: 'app_ingredient' ) ]

    public function index( IngredientRepository $ingredientRepository, PaginatorInterface $paginator, Request $request ): Response {
        // $ingredients = $ingredientRepository->findAll();

        $ingredients =
        $ingredients = $paginator->paginate(
            $ingredientRepository->findAll(),
            $request->query->getInt( 'page', 1 ),
            10
        );

        return $this->render( 'pages/ingredient/index.html.twig', [
            // 'controller_name' => 'IngredientController',
            'ingredients' => $ingredients,
        ] );
    }
}

