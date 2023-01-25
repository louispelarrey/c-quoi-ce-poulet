<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MealOrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MealOrderRepository::class)]
#[ApiResource]
class MealOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'mealOrders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Meals $meal = null;

    #[ORM\ManyToOne(inversedBy: 'mealOrders')]
    #[ORM\JoinColumn(nullable: false)]
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
