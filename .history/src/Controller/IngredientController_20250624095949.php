<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Dom\Text;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Assert\Assert;

class IngredientController extends AbstractController {
/**
 * This function Display all ingredients
 * @param IngredientRepository $ingredientRepository
 * @param PaginatorInterface $paginator
 * @param Request $request
 * @return Response
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
#[Route( '/ingredient/nouveau', name: 'app_ingredient_new', methods: ['GET', 'POST'] )]
    public function new (): Response {
        //creates a taskobject and initializes some data for this example
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        return $this->render( 'pages/ingredient/new.html.twig', [
            'form' => $form->createView(),
        ] );
    }
    public function buildForm( FormBuilderInterface $builder, array $options ): void {
        $builder
            ->add( 'name', moneyType::class, [ 
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => 3,
                    'maxlength' => 50,
                ],
                'label' => 'Prix',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' =>[
                    new Assert\Positive(),
                    new Assert\LessThan(200),
                    
                ]
            ] )
        ;
    }
}

