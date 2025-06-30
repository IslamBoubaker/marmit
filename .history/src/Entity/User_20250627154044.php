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