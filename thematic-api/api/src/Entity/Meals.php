<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\MealsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MealsRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            securityPostDenormalize: 'is_granted("MEAL_CHANGE", object)',
            securityMessage: 'Only admins can update meals.',
            denormalizationContext: ['groups' => ['meal:post']],
        ),
        new Put(
            security: 'is_granted("MEAL_CHANGE", object)',
            securityMessage: 'Only admins can edit other meals.',
        ),
        new Delete(
            security: 'is_granted("MEAL_CHANGE", object)',
            securityMessage: 'Only admins can delete other meals.',
        ),
    ],
    normalizationContext: ['groups' => ['meal:read']],
    denormalizationContext: ['groups' => ['meal:update']],
)]
class Meals
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['meal:read', 'order:read', 'restaurant:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['meal:read', 'meal:update', 'meal:post', 'order:read', 'restaurant:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['meal:read', 'meal:update', 'meal:post', 'restaurant:read'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['meal:read', 'meal:update', 'meal:post', 'restaurant:read'])]
    private ?string $picturePath = null;

    #[ORM\Column]
    #[Groups(['meal:read', 'meal:update', 'meal:post', 'restaurant:read'])]
    private ?int $price = null;

    #[ORM\Column]
    #[Groups(['meal:read', 'meal:update', 'meal:post', 'restaurant:read'])]
    private ?bool $isAvailable = true;

    #[ORM\ManyToOne(inversedBy: 'meals')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['meal:read', 'meal:post'])]
    private ?Restaurant $restaurant = null;

    #[ORM\OneToMany(mappedBy: 'meal', targetEntity: MealOrder::class, orphanRemoval: true)]
    private Collection $mealOrders;

    public function __construct()
    {
        $this->mealOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(['meal:read'])]
    public function getOrders()
    {
        return $this->mealOrders->map(fn (MealOrder $mealOrder) => $mealOrder->getOrderEntity());
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function isIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(bool $isAvailable): self
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    /**
     * @return Collection<int, MealOrder>
     */
    public function getMealOrders(): Collection
    {
        return $this->mealOrders;
    }

    public function addMealOrder(MealOrder $mealOrder): self
    {
        if (!$this->mealOrders->contains($mealOrder)) {
            $this->mealOrders->add($mealOrder);
            $mealOrder->setMeal($this);
        }

        return $this;
    }

    public function removeMealOrder(MealOrder $mealOrder): self
    {
        if ($this->mealOrders->removeElement($mealOrder)) {
            // set the owning side to null (unless already changed)
            if ($mealOrder->getMeal() === $this) {
                $mealOrder->setMeal(null);
            }
        }

        return $this;
    }
}
