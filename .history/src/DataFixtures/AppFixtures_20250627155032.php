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
    private \Faker\Generator $faker;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setName($this->faker->name())
                ->setPseudo($this->faker->word())
                ->setEmail($this->faker->unique()->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');

            $hashed = $this->hasher->hashPassword($user, 'password');
            $user->setPassword($hashed);

            $manager->persist($user);
            $users[] = $user;
        }

        $manager->flush();

        $ingredients = [];
        for ($i = 0; $i < 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word())
                       ->setPrice(mt_rand(0, 100));
            $manager->persist($ingredient);
            $ingredients[] = $ingredient;
        }

        $manager->flush();

        for ($j = 0; $j < 25; $j++) {
            $recipe = new Recipe();
            $recipe->setName($this->faker->word())
                   ->setTime(mt_rand(1, 1440))
                   ->setNbPeople(mt_rand(0, 1) === 1 ? mt_rand(1, 50) : null)
                   ->setDifficulty(mt_rand(0, 1) === 1 ? mt_rand(1, 5) : null)
                   ->setDescription($this->faker->text(300))
                   ->setPrice(mt_rand(0, 1) === 1 ? mt_rand(1, 1000) : null)
                   ->setIsFavorite((bool) mt_rand(0, 1))
                   ->setUser($users[array_rand($users)]);

            for ($k = 0; $k < mt_rand(5, 15); $k++) {
                $recipe->addIngredient($ingredients[array_rand($ingredients)]);
            }

            $manager->persist($recipe);
        }

        $manager->flush();
    }
