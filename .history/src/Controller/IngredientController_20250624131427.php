<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use RecursiveArrayIterator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class IngredientController extends AbstractController {
    /**
     * This function displays all ingredients
     */
    #[Route('/ingredient', name: 'app_ingredient')]
    public function index(
        IngredientRepository $ingredientRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $ingredients = $paginator->paginate(
            $ingredientRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient/nouveau', name: 'app_ingredient_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
            'success',
            'Votre ingredient a été créé avec succès !'
            );
            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView(),
        ]);
    
    }

 #[ Route( '/ingredient/edition/{id}', name: 'app_ingredient_edit', methods: [ 'GET', 'POST' ] ) ]

    public function edit( IngredientRepository $ingredientRepository, int $id, Request $request, EntityManagerInterface $manager ): Response {
        $ingredient = $ingredientRepository->findOneBy( [ 'id'=>$id ] );
        $form = $this->createForm( IngredientType::class, $ingredient );

        $form->handleRequest( $request );
        if ( $form->isSubmitted() && $form->isValid() ) {
            $ingredient = $form->getData();

            $manager->persist( $ingredient );
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingredient a été modifié avec succès !'
            );
            return $this->redirectToRoute( 'app_ingredient' );
        }
        return $this->render( 'pages/ingredient/edit.html.twig', [
            'form' => $form->createView(),
            'ingredient' => $ingredient,

        ] );
    }

    #[Route('/ingredient/suppression/{id}', name: 'app_ingredient_delete', methods: ['GET'])]
    public function delete(IngredientRepository $ingredientRepository, int $id, EntityManagerInterface $entityManagerInterface  ): Response {
        $ingredient = $ingredientRepository->findOneBy(['id' => $id]);

        if (!$ingredient) {
            $this->addFlash('success  ', "votre ingredient n'a pas été trouvé !");
            return $this->redirectToRoute('app_ingredient');
        }

        $manager->remove($ingredient);
        $manger->flush();

        $this->addFlash('success', 'L\'ingrédient a été supprimé avec succès !');
        return $this->redirectToRoute('app_ingredient');
    }
}

   
