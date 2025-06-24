<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class IngredientType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'ingrédient',
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => 2,
                    'maxlength' => 50,
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 50]),
                ]
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(200),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer mon ingrédient',
                'attr' => [
                    'class' => 'btn btn-primary mt-4',
                ]
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

        ] );
    }
}

}
