public function load(ObjectManager $manager): void
{
    $this->faker = Factory::create('fr_FR');

    // 1. Créer les utilisateurs AVANT les recettes
    $users = [];
    for ($i = 0; $i < 10; $i++) {
        $user = new User();
        $user->setName($this->faker->name())
             ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->word() : null)
             ->setEmail($this->faker->email())
             ->setRoles(['ROLE_USER'])
             ->setPlainPassword('password');

        $manager->persist($user);
        $users[] = $user;
    }

    $manager->flush();

    // 2. Créer les ingrédients
    $ingredients = [];
    for ($i = 0; $i < 50; $i++) {
        $ingredient = new Ingredient();
        $ingredient->setName($this->faker->word())
                   ->setPrice(mt_rand(0, 100));
        $manager->persist($ingredient);
        $ingredients[] = $ingredient;
    }

    // 3. Créer les recettes en assignant un utilisateur
    for ($j = 0; $j < 25; $j++) {
        $recipe = new Recipe();
        $recipe->setName($this->faker->word())
               ->setTime(mt_rand(1, 1440))
               ->setNbPeople(mt_rand(0, 1) ? mt_rand(1, 50) : null)
               ->setDifficulty(mt_rand(0, 1) ? mt_rand(1, 5) : null)
               ->setDescription($this->faker->text(300))
               ->setPrice(mt_rand(0, 1) ? mt_rand(1, 1000) : null)
               ->setIsFavorite((bool) mt_rand(0, 1))
               ->setUser($users[array_rand($users)]); // ✅ utilisateurs bien définis

        // Ajouter des ingrédients
        for ($k = 0; $k < mt_rand(5, 15); $k++) {
            $recipe->addIngredient($ingredients[array_rand($ingredients)]);
        }

        $manager->persist($recipe);
    }

    $manager->flush();
}
