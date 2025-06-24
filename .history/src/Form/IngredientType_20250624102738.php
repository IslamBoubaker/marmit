<?php

namespace App\Form;

use App\Entity\Ingredient;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('price')
            -
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }

       public function buildForm( FormBuilderInterface $builder, array $options ): void {
        $builder
            ->add( 'price', MoneyType::class, [ 
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
                    new Assert\LessThan(200)
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                'class' => 'btn btn-primary mt-4',
                ],
                'label' => 'creer mon ingredient',
            ])
        ;
        
    }
}
