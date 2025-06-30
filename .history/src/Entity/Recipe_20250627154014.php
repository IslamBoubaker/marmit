
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
