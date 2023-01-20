<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use App\Repository\ReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

//TODO : add security on the report entity

#[ORM\Entity(repositoryClass: ReportRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            security: 'is_granted("IS_AUTHENTICATED_FULLY")',
            securityMessage: 'Only admins can create reports.',
            denormalizationContext: ['groups' => ['report:post']],
        ),
        new Put(
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can edit reports.',
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can create reports.',
        ),
    ],
    normalizationContext: ['groups' => ['report:read']],
    denormalizationContext: ['groups' => ['report:update']],
)]
class Report
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['report:read', 'user:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reports')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['report:read', 'report:post'])]
    private ?User $reportedBy = null;

    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['report:read', 'user:read', 'report:post'])]
    private ?User $reportedUser = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['report:read', 'user:read', 'report:post', 'report:update'])]
    private ?string $reason = null;

    #[ORM\Column(type: Types::BINARY)]
    private $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReportedBy(): ?User
    {
        return $this->reportedBy;
    }

    public function setReportedBy(?User $reportedBy): self
    {
        $this->reportedBy = $reportedBy;

        return $this;
    }

    public function getReportedUser(): ?User
    {
        return $this->reportedUser;
    }

    public function setReportedUser(?User $reportedUser): self
    {
        $this->reportedUser = $reportedUser;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }
}
