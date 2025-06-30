<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    private $faker;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // --- 1. Création des utilisateurs ---
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setName($this->faker->name())
                ->setPseudo(mt_rand(0, 1) ? $this->faker->word() : 'user'.$i)  // on met toujours une string
                ->setEmail($this->faker->unique()->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');

            // Hasher le password si tu utilises un listener, sinon faire ici
            $manager->persist($user);
            $users[] = $user;
        }
        $manager->flush();

        // --- 2. Création des ingrédients ---
        $ingredients = [];
        for ($i = 0; $i < 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word());
            $ingredient->setPrice(mt_rand(0, 100));
            $manager->persist($ingredient);
            $ingredients[] = $ingredient;
        }
        $manager->flush();

        // --- 3. Création des recettes ---
        for ($i = 0; $i < 25; $i++) {
            $recipe = new Recipe();
            $recipe->setName($this->faker->word())
                ->setTime(mt_rand(1, 1440))
                ->setNbPeople(mt_rand(0, 1) === 1 ? mt_rand(1, 50) : null)
                ->setDifficulty(mt_rand(0, 1) === 1 ? mt_rand(1, 5) : null)
                ->setDescription($this->faker->text(300))
                ->setPrice(mt_rand(0, 1) === 1 ? mt_rand(1, 1000) : null)
                ->setIsFavorite(mt_rand(0, 1) === 1)
                ->setUser($users[array_rand($users)]); // assigner un user obligatoirement

            // Ajout d'ingrédients
            $countIngredients = mt_rand(5, 15);
            for ($j = 0; $j < $countIngredients; $j++) {
                $recipe->addIngredient($ingredients[array_rand($ingredients)]);
            }

            $manager->persist($recipe);
 }
        $manager->flush();
  }
}