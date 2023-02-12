<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\AcceptDeliveryController;
use App\Controller\DeliveredDeliveryController;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(
            security: 'is_granted("ORDER_GET", object)',
            securityMessage: 'Only admins can get other orders.',
        ),
        new Post(
            security: 'is_granted("ROLE_USER")',
            securityMessage: 'Only users can create orders.',
        ),
        new Patch(
            security: 'is_granted("ORDER_CAN_DELIVER", object)',
            securityMessage: 'Only deliverymen can deliver deliveries.',
            uriTemplate: '/orders/{id}/delivered',
            controller: DeliveredDeliveryController::class,
        ),
        new Patch(
            security: 'is_granted("ORDER_CAN_ACCEPT", object)',
            securityMessage: 'Only deliverymen can accept deliveries.',
            uriTemplate: '/orders/{id}/accepted',
            controller: AcceptDeliveryController::class,
        ),
        new Put(
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only users can edit their orders.',
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only users can delete their orders.',
        ),
    ],
    normalizationContext: ['groups' => ['order:read']],
    denormalizationContext: ['groups' => ['order:update']],
)]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["order:read", "user:read", "meal:read"])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(["order:read"])]
    private ?string $status = "opened";

    #[ORM\OneToMany(mappedBy: 'orderEntity', targetEntity: MealOrder::class, orphanRemoval: true)]
    private Collection $mealOrders;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[Groups(["order:read", "order:update"])]
    private ?User $client = null;

    #[ORM\ManyToOne(inversedBy: 'deliveryOrders')]
    #[Groups(["order:read", "order:deliver"])]
    private ?User $deliverer = null;

    #[ORM\ManyToOne(inversedBy: 'restaurantOrders')]
    #[Groups(["order:read", "order:update"])]
    private ?User $restaurantUser = null;

    public function __construct()
    {
        $this->mealOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(["order:read"])]
    public function getMeals()
    {
        return $this->mealOrders->map(function (MealOrder $mealOrder) {
            return $mealOrder->getMeal();
        });
    }

    #[Groups(["order:read"])]
    public function getTotalPrice()
    {
        return $this->mealOrders->map(function (MealOrder $mealOrder) {
            return $mealOrder->getMeal()->getPrice();
        })->reduce(function ($carry, $item) {
            return $carry + $item;
        });
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
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

    public function getDeliverer(): ?User
    {
        return $this->deliverer;
    }

    public function setDeliverer(?User $deliverer): self
    {
        $this->deliverer = $deliverer;

        return $this;
    }

    public function getRestaurantUser(): ?User
    {
        return $this->restaurantUser;
    }

    public function setRestaurantUser(?User $restaurantUser): self
    {
        $this->restaurantUser = $restaurantUser;

        return $this;
    }
}
