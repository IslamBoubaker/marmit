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
        // Création utilisateurs
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setName($this->faker->name())
                ->setPseudo(mt_rand(0, 1) ? $this->faker->word() : null)
                ->setEmail($this->faker->unique()->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');
            // Hasher le mot de passe si nécessaire (sinon dans listener)
            // $hashed = $this->hasher->hashPassword($user, 'password');
            // $user->setPassword($hashed);
            $manager->persist($user);
            $users[] = $user;
        }
        $manager->flush();

        // Création ingrédients
        $ingredients = [];
        for ($i = 0; $i < 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word())
                ->setPrice(mt_rand(0, 100));
            $manager->persist($ingredient);
            $ingredients[] = $ingredient;
        }
        $manager->flush();

        // Création recettes
      // Création utilisateurs
$users = [];
for ($i = 0; $i < 10; $i++) {
    $user = new User();
    $user->setName($this->faker->name())
         ->setPseudo(mt_rand(0,1) ? $this->faker->word() : null)
         ->setEmail($this->faker->unique()->email())
         ->setRoles(['ROLE_USER'])
         ->setPlainPassword('password');
    $manager->persist($user);
    $users[] = $user;
}
$manager->flush();

// Création recettes
for ($i = 0; $i < 25; $i++) {
    $recipe = new Recipe();
    $recipe->setName($this->faker->word())
           ->setUser($users[array_rand($users)]); // assigner utilisateur valide
    $manager->persist($recipe);
}
$manager->flush();

}}
