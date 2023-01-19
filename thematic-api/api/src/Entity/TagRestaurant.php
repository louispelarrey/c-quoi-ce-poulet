<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Repository\TagRestaurantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TagRestaurantRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            security: 'is_granted("TAG_RESTAURANT_CHANGE", object)',
            securityMessage: 'Only admins can create tags.',
        ),
        new Post(
            security: 'is_granted("TAG_RESTAURANT_CHANGE", object)',
            securityMessage: 'Only admins can create tags.',
        ),
        new Delete(
            security: 'is_granted("TAG_RESTAURANT_CHANGE", object)',
            securityMessage: 'Only admins can create tags.',
        ),
    ],
    normalizationContext: ['groups' => ['tagRestaurant:read']],
    denormalizationContext: ['groups' => ['tagRestaurant:create', 'tagRestaurant:update']],
)]
class TagRestaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tagRestaurants')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['tagRestaurant:read', 'tagRestaurant:create', 'tagRestaurant:update', 'restaurant:read'])]
    private ?Tag $tag = null;

    #[ORM\ManyToOne(inversedBy: 'tagRestaurants')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['tagRestaurant:read', 'tagRestaurant:create', 'tagRestaurant:update'])]
    private ?Restaurant $restaurant = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): self
    {
        $this->tag = $tag;

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
}
