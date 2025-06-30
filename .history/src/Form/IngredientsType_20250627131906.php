<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Ingredient;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\OptionsResolver\OptionsResolver;use Symfony\Component\Validator\Constraints as Assert;

class IngredientsType extends AbstractType
{

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

}
