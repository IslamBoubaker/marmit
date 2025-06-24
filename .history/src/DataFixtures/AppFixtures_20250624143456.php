<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use phpDocumentor\Reflection\DocBlock\Tags\Generic;

class AppFixtures extends Fixture
{


    private Generator $faker;
    public function __construct() 
    {
        $this->faker = Factory::create("fr_FR");
    }

    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i < 50 ; $i++) { 
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word());
            $ingredient->setPrice(mt_rand(0,200));
            $manager->persist($ingredient);
        }

        for ($i=1; $i < 50 ; $i++) { 
            $recipe = new Recipe();
            $recipe->setName($this->faker->word())
                    ->setTime(mt_rand(1,1440))
                    ->setNbPeople(mt_rand(0,1) == 1? mt_rand(1,50):null)
                    ->setDifficulty(mt_rand(0,1) == 1? mt_rand(1,5):null)
                    ->setDescription($this->faker->text(300))
                    ->setPrice(mt_rand(0,1) == 1? mt_rand(1,1000):null)
                    ->setIsFavorite(mt_rand(0,1) ==1? true:false);

for $k=0; $k < mt_rand(5,15); $k++) {
    $recipe->addIngredient($ingredient)

            $recipe->setPrice(mt_rand(0,200));
            $manager->persist($ingredient);
        }

        $manager->flush();
    }

}