<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\IngredientRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @param RecipeRepository $recipeRepository
 * @param PaginatorInterface $paginator
 * @param Request $request
 * @return Response 
 */
class RecipeController extends AbstractController {
    #[ Route( '/recette', name: 'app_recipe_index', methods: [ 'GET' ] ) ]
    public function index( RecipeRepository $recipeRepository, PaginatorInterface $paginator, Request $request ): Response {
        $recipes = $paginator->paginate(
            $recipeRepository->findBy('user'),
            $request->query->getInt( 'page', 1 ),
            10
        );
        return $this->render( 'pages/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);

        // return $this->render( 'pages/recipe/index.html.twig', [
        //     'recipes' => $recipeRepository->findAll(),

        // ] );
    }
    #[ Route( '/recette/creation', name: 'app_recipe_new', methods: [ 'GET', 'POST' ] ) ]

    public function new( Request $request, EntityManagerInterface $entityManager ): Response {
        $recipe = new Recipe();
        $form = $this->createForm( RecipeType::class, $recipe );


        $form->handleRequest( request: $request );
        if ($form->isSubmitted() && $form->isValid() ) {
            $entityManager->persist( $recipe );
            $entityManager->flush();
            $this->addFlash( 'success', 'Votre recette a été créée avec succès !' );

            return $this->redirectToRoute( 'app_recipe');
        }

        return $this->renderForm( 'pages/recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form
        ] );
    }

    // #[ Route( '/{id}', name: 'app_recipe_show', methods: [ 'GET' ] ) ]

    // public function show( Recipe $recipe ): Response {
    //     return $this->render( 'pages/recipe/show.html.twig', [
    //         'recipe' => $recipe,
    //     ] );
    // }

#[ Route( '/{id}/edition', name: 'app_recipe_edit', methods: [ 'GET', 'POST' ] ) ]
public function edit(Request $request, Recipe $recipe, EntityManagerInterface $manager ,RecipeRepository $recipeRepository, int $id ): Response
    {
;       $form = $this->createForm(RecipeType:: class, $recipe);

       $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid()){
        $recipe = $form->getData(); 

        $manager->flush();
        $this->addFlash(
            'success',
            'votre recette a été modifiée avec succès !'
        );
            return $this->redirectToRoute( 'app_recipe_index');
        }

        return $this->render( 'pages/recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView()
        ] );
    }

    #[ Route( '/recette/suppression/{id}', name: 'app_recipe_delete', methods: ['GET', 'POST'] ) ]

    public function delete( Request $request, Recipe $recipe, EntityManagerInterface $manager ,RecipeRepository $recipeRepository, int $id ): Response {   

        if(!$recipe){
            $this->addFlash(
            'success',
            "Votre Recette n'a pas été trouvée !"

        );
        return $this->redirectToRoute( 'app_ingredient_index');
    }
    $manager->remove($recipe);
    $manager->flush();

    $this->addFlash(
        'success',
        'votre recette a été supprimée avec succés'
        
    );
            return $this->redirectToRoute( 'app_recipe_index');

}
}