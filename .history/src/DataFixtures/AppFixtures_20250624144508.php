<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create("fr_FR");
    }

    public function load(ObjectManager $manager): void
    {
        $ingredients = [];

        // Création des ingrédients
        for ($i = 1; $i <= 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word());
            $ingredient->setPrice(mt_rand(1, 100));
            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }

        // Création des recettes
        for ($j = 1; $j <= 25; $j++) {
            $recipe = new Recipe();
            $recipe->setName($this->faker->word())
                   ->setTime(mt_rand(1, 1440))
                   ->setNbPeople(mt_rand(0, 1) ? mt_rand(1, 50) : null)
                   ->setDifficulty(mt_rand(0, 1) ? mt_rand(1, 5) : null)
                   ->setDescription($this->faker->text(300))
                   ->setPrice(mt_rand(0, 1) ? mt_rand(1, 1000) : null)
                   ->setIsFavorite((bool)mt_rand(0, 1));

            // Ajout aléatoire d'ingrédients
            $numIngredients = mt_rand(5, 15);
            $randomKeys = array_rand($ingredients, $numIngredients);
            foreach ((array) $randomKeys as $key) {
                $recipe->addIngredient($ingredients[$key]);
            }

            $manager->persist($recipe);
        }

        $manager->flush();
    }
}
