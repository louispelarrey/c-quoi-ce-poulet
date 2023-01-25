<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
#[ApiResource]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalPrice = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(type: Types::JSON)]
    private array $status = [];

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?User $client = null;

    #[ORM\OneToMany(mappedBy: 'orderEntity', targetEntity: MealOrder::class, orphanRemoval: true)]
    private Collection $mealOrders;

    public function __construct()
    {
        $this->mealOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(?int $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

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

    public function getStatus(): array
    {
        return $this->status;
    }

    public function setStatus(array $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

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
            $mealOrder->setOrderEntity($this);
        }

        return $this;
    }

    public function removeMealOrder(MealOrder $mealOrder): self
    {
        if ($this->mealOrders->removeElement($mealOrder)) {
            // set the owning side to null (unless already changed)
            if ($mealOrder->getOrderEntity() === $this) {
                $mealOrder->setOrderEntity(null);
            }
        }

        return $this;
    }

}
