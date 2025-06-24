<?php

namespace App\Controller;

use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController {
    #[ Route( '/ingredient', name: 'app_ingredient' ) ]

    public function index(IngredientRepository $ingredientRepository): Response {
        $ingred
        return $this->render( 'pages/ingredient/index.html.twig', [
            'controller_name' => 'IngredientController',
        ] );
    }
}
