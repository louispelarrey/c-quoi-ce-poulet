<?php
# api/src/Entity/User.php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\ResetPasswordController;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\State\UserPasswordHasher;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Patch;
use App\Repository\RestaurantRepository;

#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['restaurant:read']],
            security: 'is_granted("ROLE_USER") or object == user',
            securityMessage: 'Only users can view restaurants.',
        ),
        new Get(
            security: 'is_granted("ROLE_USER") or object == user',
            securityMessage: 'Only users can view restaurants.',
        ),
        new Post(
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can create restaurants.',
            processor: UserPasswordHasher::class,
        ),
        new Put(
            processor: UserPasswordHasher::class,
            security: 'is_granted("ROLE_ADMIN") or object == user',
            securityMessage: 'Only admins can edit other restaurants.',
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN") or object == user',
            securityMessage: 'Only admins can delete other restaurants.',
        ),
    ],
    normalizationContext: ['groups' => ['restaurant:read']],
    denormalizationContext: ['groups' => ['restaurant:create', 'restaurant:update']],
)]
#[ApiFilter(SearchFilter::class, properties: ['email' => 'exact'])]
#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
#[ORM\Table(name: '`restaurant`')]
#[UniqueEntity('email')]
class Restaurant implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups([
        'restaurant:read',
        'restaurant:create',
        'restaurant:update',
        'tag:read',
        'meal:read',
    ])]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private ?string $password = null;

    #[Assert\NotBlank(groups: ['restaurant:create'])]
    #[Groups(['restaurant:create', 'restaurant:update'])]
    private ?string $plainPassword = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    #[Groups(['restaurant:read', 'restaurant:update', 'restaurant:create', 'tag:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['restaurant:read', 'restaurant:update'])]
    private ?string $picturePath = null;

    #[ORM\Column(length: 255)]
    #[Groups(['restaurant:read', 'restaurant:update'])]
    private ?string $address = null;

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['restaurant:read', 'restaurant:update'])]
    private array $openingTime = [];

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Meal::class)]
    #[Groups(['restaurant:read', 'restaurant:update'])]
    private Collection $meal;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: TagRestaurant::class, orphanRemoval: true)]
    #[Groups(['restaurant:read'])]
    private Collection $tagRestaurants;

    public function __construct()
    {
        $this->meal = new ArrayCollection();
        $this->tagRestaurants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $painPassword): self
    {
        $this->plainPassword = $painPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

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
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
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
}
