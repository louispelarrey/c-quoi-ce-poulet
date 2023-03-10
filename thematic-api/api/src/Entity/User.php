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

#[ApiResource(
    operations: [
        new GetCollection(
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can view other users.',
        ),
        new Post(
            processor: UserPasswordHasher::class,
        ),
        new Get(
            security: 'is_granted("ROLE_ADMIN") or object == user',
            securityMessage: 'Only admins can view other users.',
        ),
        new Put(
            security: 'is_granted("ROLE_ADMIN") or object == user',
            securityMessage: 'Only admins can edit other users.',
            securityPostDenormalizeMessage: 'Only admins can edit roles.',
            processor: UserPasswordHasher::class
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN") or object == user',
            securityMessage: 'Only admins can delete users.',
        ),
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:create', 'user:update']],
)]
#[ApiFilter(SearchFilter::class, properties: ['email' => 'exact'])]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity('email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    #[Groups(['user:read', 'restaurant:read', 'report:read', 'order:read'])]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(['user:read', 'user:create', 'user:update', 'restaurant:read', 'report:read', 'order:read'])]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private ?string $password = null;

    #[Assert\NotBlank(groups: ['user:create'])]
    #[Groups(['user:create', 'user:update'])]
    private ?string $plainPassword = null;

    #[ORM\Column(type: 'json')]
    #[Groups(['user:read', 'user:update'])]
    #[ApiProperty(securityPostDenormalize: 'is_granted("USER_EDIT", object)')]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:create', 'user:update', 'restaurant:read', 'report:read', 'order:read'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read', 'user:create', 'user:update', 'restaurant:read', 'report:read', 'order:read'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 20)]
    #[Groups(['user:read', 'user:create', 'user:update', 'restaurant:read' , 'report:read'])]
    private ?string $numberPhone = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:create', 'user:update', 'restaurant:read', 'report:read'])]
    private ?string $address = null;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Restaurant::class, orphanRemoval: true)]
    #[Groups(['user:read'])]
    private Collection $restaurants;

    #[ORM\OneToMany(mappedBy: 'reportedBy', targetEntity: Report::class)]
    #[Groups(['user:read'])]
    private Collection $reportsBy;

    #[ORM\OneToMany(mappedBy: 'reportedUser', targetEntity: Report::class)]
    #[Groups(['user:read'])]
    private Collection $reportsOn;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Order::class)]
    #[Groups(['user:read'])]
    private Collection $orders;

    #[ORM\OneToMany(mappedBy: 'deliverer', targetEntity: Order::class)]
    #[Groups(['user:read'])]
    private Collection $deliveryOrders;

    #[ORM\OneToMany(mappedBy: 'restaurantUser', targetEntity: Order::class)]
    #[Groups(['user:read'])]
    private Collection $restaurantOrders;

    public function __construct()
    {
        $this->restaurants = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->reportsBy = new ArrayCollection();
        $this->reportsOn = new ArrayCollection();
        $this->deliveryOrders = new ArrayCollection();
        $this->restaurantOrders = new ArrayCollection();
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getNumberPhone(): ?string
    {
        return $this->numberPhone;
    }

    public function setNumberPhone(string $numberPhone): self
    {
        $this->numberPhone = $numberPhone;

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

    /**
     * @return Collection<int, Restaurant>
     */
    public function getRestaurants(): Collection
    {
        return $this->restaurants;
    }

    public function addRestaurant(Restaurant $restaurant): self
    {
        if (!$this->restaurants->contains($restaurant)) {
            $this->restaurants->add($restaurant);
            $restaurant->setOwner($this);
        }

        return $this;
    }

    public function removeRestaurant(Restaurant $restaurant): self
    {
        if ($this->restaurants->removeElement($restaurant)) {
            // set the owning side to null (unless already changed)
            if ($restaurant->getOwner() === $this) {
                $restaurant->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setClient($this);
        }

        return $this;
    }
     /**
     * @return Collection<int, Report>
     */
    public function getReportsBy(): Collection
    {
        return $this->reportsBy;
    }

    public function addReportsBy(Report $reportsBy): self
    {
        if (!$this->reportsBy->contains($reportsBy)) {
            $this->reportsBy->add($reportsBy);
            $reportsBy->setReportedBy($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getClient() === $this) {
                $order->setClient(null);
            }

            return $this;
        }
    }

    public function removeReportsBy(Report $reportsBy): self
    {
        if ($this->reportsBy->removeElement($reportsBy)) {
            // set the owning side to null (unless already changed)
            if ($reportsBy->getReportedBy() === $this) {
                $reportsBy->setReportedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Report>
     */
    public function getReportsOn(): Collection
    {
        return $this->reportsOn;
    }

    public function addReportsOn(Report $reportsOn): self
    {
        if (!$this->reportsOn->contains($reportsOn)) {
            $this->reportsOn->add($reportsOn);
            $reportsOn->setReportedUser($this);
        }

        return $this;
    }

    public function removeReportsOn(Report $reportsOn): self
    {
        if ($this->reportsOn->removeElement($reportsOn)) {
            // set the owning side to null (unless already changed)
            if ($reportsOn->getReportedUser() === $this) {
                $reportsOn->setReportedUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getDeliveryOrders(): Collection
    {
        return $this->deliveryOrders;
    }

    public function addDeliveryOrder(Order $deliveryOrder): self
    {
        if (!$this->deliveryOrders->contains($deliveryOrder)) {
            $this->deliveryOrders->add($deliveryOrder);
            $deliveryOrder->setDeliverer($this);
        }

        return $this;
    }

    public function removeDeliveryOrder(Order $deliveryOrder): self
    {
        if ($this->deliveryOrders->removeElement($deliveryOrder)) {
            // set the owning side to null (unless already changed)
            if ($deliveryOrder->getDeliverer() === $this) {
                $deliveryOrder->setDeliverer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getRestaurantOrders(): Collection
    {
        return $this->restaurantOrders;
    }

    public function addRestaurantOrder(Order $restaurantOrder): self
    {
        if (!$this->restaurantOrders->contains($restaurantOrder)) {
            $this->restaurantOrders->add($restaurantOrder);
            $restaurantOrder->setRestaurantUser($this);
        }

        return $this;
    }

    public function removeRestaurantOrder(Order $restaurantOrder): self
    {
        if ($this->restaurantOrders->removeElement($restaurantOrder)) {
            // set the owning side to null (unless already changed)
            if ($restaurantOrder->getRestaurantUser() === $this) {
                $restaurantOrder->setRestaurantUser(null);
            }
        }

        return $this;
    }
}
