<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity('email')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\EntityListeners(['App\EntityListener\UserListener'])] 
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Length(min:2,max:180)]
    #[Assert\Email()]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private array $roles = [];

    private ?string $plainPassword = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank()]
    private ?string $password = 'password';

    #[Assert\NotBlank()]
    #[Assert\Length(min:2,max:50)]
    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[Assert\Length(min:2,max:50)]
    #[ORM\Column(length: 50,nullable:true)]
    private ?string $pseudo = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(targetEntity: Recipe::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $User;

    // #[ORM\OneToMany(targetEntity: ingredient::class, mappedBy: 'user', orphanRemoval: true)]
    // private Collection $ingredients;

    // #[ORM\OneToMany(targetEntity: Recipe::class, mappedBy: 'recipe')]
    // private Collection $recipes;

    public function __construct(){

            $this->createdAt = new \DateTimeImmutable();
            $this->ingredients = new ArrayCollection();
            $this->recipes = new ArrayCollection();
            $this->User = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /*
    * Get the Value of plainPassword
    */


        public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /** 
    * Set the Value of plainPassword
    *
    * @return self
    */
       public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
}

       /**
        * @return Collection<int, ingredient>
        */
       public function getIngredients(): Collection
       {
           return $this->ingredients;
       }

       public function addIngredient(ingredient $ingredient): static
       {
           if (!$this->ingredients->contains($ingredient)) {
               $this->ingredients->add($ingredient);
               $ingredient->setUser($this);
           }

           return $this;
       }

       public function removeIngredient(ingredient $ingredient): static
       {
           if ($this->ingredients->removeElement($ingredient)) {
               // set the owning side to null (unless already changed)
               if ($ingredient->getUser() === $this) {
                   $ingredient->setUser(null);
               }
           }

           return $this;
       }

    //    /**
    //     * @return Collection<int, Recipe>
    //     */
    //    public function getRecipes(): Collection
    //    {
    //        return $this->recipes;
    //    }

//        public function addRecipe(Recipe $recipe): static
//        {
//            if (!$this->recipes->contains($recipe)) {
//                $this->recipes->add($recipe);
//                $recipe->setRecipe($this);
//            }

//            return $this;
//        }

//        public function removeRecipe(Recipe $recipe): static
//        {
//            if ($this->recipes->removeElement($recipe)) {
//                // set the owning side to null (unless already changed)
//                if ($recipe->getRecipe() === $this) {
//                    $recipe->setRecipe(null);
//                }
//            }

//            return $this;
//        }

/**
 * @return Collection<int, Recipe>
 */
public function getUser(): Collection
{
    return $this->User;
}

public function addUser(Recipe $user): static
{
    if (!$this->User->contains($user)) {
        $this->User->add($user);
        $user->setUser($this);
    }

    return $this;
}

public function removeUser(Recipe $user): static
{
    if ($this->User->removeElement($user)) {
        // set the owning side to null (unless already changed)
        if ($user->getUser() === $this) {
            $user->setUser(null);
        }
    }

    return $this;
}
}