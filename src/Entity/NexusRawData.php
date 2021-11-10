<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\NexusRawDataRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[
    ORM\Entity(repositoryClass: NexusRawDataRepository::class),
    ORM\Table(name: 'nexus_raw_data'),
    ORM\Index(fields: ['submitter'], name: 'submitter_idx'),
]
class NexusRawData
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(name: 'submitted_at', type: 'datetimetz_immutable', nullable: false)]
    private ?DateTimeImmutable $submittedAt = null;

    #[
        ORM\ManyToOne(targetEntity: User::class),
        ORM\JoinColumn(name: 'submitter_id', referencedColumnName: 'id', nullable: false),
    ]
    private ?User $submitter = null;

    #[ORM\Column(name: 'data', type: 'json', nullable: false)]
    private $data = [];

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function getSubmittedAt(): ?DateTimeImmutable
    {
        return $this->submittedAt;
    }

    public function setSubmittedAt(?DateTimeImmutable $submittedAt): void
    {
        $this->submittedAt = $submittedAt;
    }

    public function getSubmitter(): ?User
    {
        return $this->submitter;
    }

    public function setSubmitter(?User $submitter): void
    {
        $this->submitter = $submitter;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }
}
