<?php

// src/Entity/Recipe.php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[UniqueEntity('name')]
#[ORM\HasLifecycleCallbacks]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(min: 2, max: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column]
    private ?bool $isFavorite = null;

    #[ORM\ManyToMany(targetEntity: Ingredient::class, inversedBy: 'recipes')]
    private Collection $ingredients;

    #[ORM\Column(nullable: true)]
    private ?int $time = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbPeople = null;

    #[ORM\Column(nullable: true)]
    private ?int $difficulty = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }

    // Getters/setters (abrégés ici pour clarté)
    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): static { $this->user = $user; return $this; }
    public function getIngredients(): Collection { return $this->ingredients; }
    public function addIngredient(Ingredient $ingredient): static {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
        }
        return $this;
    }
}


// src/Entity/User.php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity('email')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email()]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    #[Assert\NotBlank()]
    private ?string $password = null;

    private ?string $plainPassword = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    private ?string $name = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $pseudo = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(targetEntity: Recipe::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $recipes;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->recipes = new ArrayCollection();
    }

    public function getRecipes(): Collection { return $this->recipes; }
    public function addRecipe(Recipe $recipe): static {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->setUser($this);
        }
        return $this;
    }
    public function removeRecipe(Recipe $recipe): static {
        if ($this->recipes->removeElement($recipe)) {
            if ($recipe->getUser() === $this) {
                $recipe->setUser(null);
            }
        }
        return $this;
    }
}


// src/DataFixtures/AppFixtures.php

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
}
