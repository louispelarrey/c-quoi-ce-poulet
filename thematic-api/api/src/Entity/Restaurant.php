<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\State\UserPasswordHasher;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\RestaurantRepository;


#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can create restaurants.',
        ),
        new Put(
            security: 'is_granted("RESTAURANT_EDIT", object)',
            securityMessage: 'Only admins can edit other restaurants.',
        ),
        new Delete(
            security: 'is_granted("RESTAURANT_EDIT", object)',
            securityMessage: 'Only admins can delete other restaurants.',
        ),
    ],
    normalizationContext: ['groups' => ['restaurant:read']],
    denormalizationContext: ['groups' => ['restaurant:create', 'restaurant:update']],
)]
class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['restaurant:read', 'user:read', 'tag:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['restaurant:read', 'restaurant:update', 'restaurant:create', 'tag:read', 'user:read', 'tag:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['restaurant:read', 'restaurant:update'])]
    private ?string $picturePath = null;

    #[ORM\Column(length: 255)]
    #[Groups(['restaurant:read', 'restaurant:update'])]
    private ?string $address = null;

    #[ORM\Column]
    #[Groups(['restaurant:read', 'restaurant:update'])]
    private array $openingTime = [];

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Meal::class)]
    #[Groups(['restaurant:read', 'restaurant:update'])]
    private Collection $meal;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: TagRestaurant::class, orphanRemoval: true)]
    private Collection $tagRestaurants;

    #[ORM\ManyToOne(inversedBy: 'restaurants')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['restaurant:read', 'restaurant:update'])]
    private ?User $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(['restaurant:read'])]
    public function getTags(): Collection
    {
        return $this->tagRestaurants->map(fn (TagRestaurant $tagRestaurant) => $tagRestaurant->getTag());
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPicturePath(): ?string
    {
        return $this->picturePath;
    }

    public function setPicturePath(?string $picturePath): self
    {
        $this->picturePath = $picturePath;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getOpeningTime(): array
    {
        return $this->openingTime;
    }

    public function setOpeningTime(array $openingTime): self
    {
        $this->openingTime = $openingTime;

        return $this;
    }


    /**
     * @return Collection<int, Meal>
     */
    public function getMeal(): Collection
    {
        return $this->meal;
    }

    public function addMeal(Meal $meal): self
    {
        if (!$this->meal->contains($meal)) {
            $this->meal->add($meal);
            $meal->setRestaurant($this);
        }

        return $this;
    }

    public function removeMeal(Meal $meal): self
    {
        if ($this->meal->removeElement($meal)) {
            // set the owning side to null (unless already changed)
            if ($meal->getRestaurant() === $this) {
                $meal->setRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TagRestaurant>
     */
    public function getTagRestaurants(): Collection
    {
        return $this->tagRestaurants;
    }

    public function addTagRestaurant(TagRestaurant $tagRestaurant): self
    {
        if (!$this->tagRestaurants->contains($tagRestaurant)) {
            $this->tagRestaurants->add($tagRestaurant);
            $tagRestaurant->setRestaurant($this);
        }

        return $this;
    }

    public function removeTagRestaurant(TagRestaurant $tagRestaurant): self
    {
        if ($this->tagRestaurants->removeElement($tagRestaurant)) {
            // set the owning side to null (unless already changed)
            if ($tagRestaurant->getRestaurant() === $this) {
                $tagRestaurant->setRestaurant(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
