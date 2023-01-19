<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can create tags.',
        ),
        new Put(
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can create tags.',
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can create tags.',
        ),
    ],
    normalizationContext: ['groups' => ['tag:read']],
    denormalizationContext: ['groups' => ['tag:create', 'tag:update']],

)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tag:read', 'tagRestaurant:read', 'restaurant:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tag:read', 'tag:create', 'tag:update', 'tagRestaurant:read', 'restaurant:read'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'tag', targetEntity: TagRestaurant::class, orphanRemoval: true)]
    private Collection $tagRestaurants;

    public function __construct()
    {
        $this->tagRestaurants = new ArrayCollection();
    }

    #[Groups(['tag:read'])]
    public function getRestaurants(): Collection
    {
        return $this->tagRestaurants->map(fn (TagRestaurant $tagRestaurant) => $tagRestaurant->getRestaurant());
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $tagRestaurant->setTag($this);
        }

        return $this;
    }

    public function removeTagRestaurant(TagRestaurant $tagRestaurant): self
    {
        if ($this->tagRestaurants->removeElement($tagRestaurant)) {
            // set the owning side to null (unless already changed)
            if ($tagRestaurant->getTag() === $this) {
                $tagRestaurant->setTag(null);
            }
        }

        return $this;
    }
}
