<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Repository\MealOrderRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MealOrderRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            security: 'is_granted("ROLE_USER")',
            securityMessage: 'Only user can get meal orders.',
        ),
        new Post(
            securityPostDenormalize: 'is_granted("MEAL_ORDER_CHANGE", object)',
            securityMessage: 'Only client can create meal orders.',
        ),
        new Delete(
            security: 'is_granted("MEAL_ORDER_CHANGE", object)',
            securityMessage: 'Only client can delete meal orders.',
        ),
    ],
    normalizationContext: ['groups' => ['mealOrder:read']],
    denormalizationContext: ['groups' => ['mealOrder:update']],
)]
class MealOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["mealOrder:read"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'mealOrders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["mealOrder:read", "mealOrder:update", "order:read"])]
    private ?Meals $meal = null;

    #[ORM\ManyToOne(inversedBy: 'mealOrders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["mealOrder:read", "mealOrder:update", "meals:read"])]
    private ?Order $orderEntity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMeal(): ?Meals
    {
        return $this->meal;
    }

    public function setMeal(?Meals $meal): self
    {
        $this->meal = $meal;

        return $this;
    }

    public function getOrderEntity(): ?Order
    {
        return $this->orderEntity;
    }

    public function setOrderEntity(?Order $orderEntity): self
    {
        $this->orderEntity = $orderEntity;

        return $this;
    }
}
