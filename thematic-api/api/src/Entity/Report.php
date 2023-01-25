<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use App\Repository\ReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

//TODO : add security on the report entity

#[ORM\Entity(repositoryClass: ReportRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(
            security: 'is_granted("IS_AUTHENTICATED_FULLY")',
            securityMessage: 'Only admins can create reports.',
            denormalizationContext: ['groups' => ['report:post']],
        ),
        new Get(),
        new Put(
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can edit reports.',
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can delete reports.',
        ),
    ],
    normalizationContext: ['groups' => ['report:read']],
    denormalizationContext: ['groups' => ['report:update']],
)]
#[ApiFilter(BooleanFilter::class, properties: ['status'])]
class Report
{
    const STATUS_PENDING = 'pending';
    const STATUS_DONE = 'done';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['report:read', 'user:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reportsBy')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['report:read'])]
    private ?User $reportedBy = null;

    #[ORM\ManyToOne(inversedBy: 'reportsOn')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['report:read', 'user:read', 'report:post'])]
    private ?User $reportedUser = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['report:read', 'user:read', 'report:post', 'report:update'])]
    private ?string $reason = null;

    #[ORM\Column]
    private array $status = [];

    /*#[ORM\Column(type: Types::STRING, nullable: false)]
    #[Groups(['report:read', 'user:read', 'report:update'])]
    private $status = self::STATUS_PENDING;*/


    public function __construct()
    {
    }

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

    public function getStatus(): array
    {
        return $this->status;
    }

    public function setStatus(array $status): self
    {
        $this->status = $status;

        return $this;
    }

}