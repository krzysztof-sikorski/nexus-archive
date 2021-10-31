<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\NexusRequestLog\Request;
use App\Entity\NexusRequestLog\Response;
use App\Repository\NexusRequestLogRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Uid\Uuid;

#[
    ORM\Entity(repositoryClass: NexusRequestLogRepository::class),
    ORM\Table(name: 'nexus_request_log'),
    ORM\UniqueConstraint(name: 'request_sort_uniq', columns: ['request_started_at', 'request_id']),
    ORM\Index(fields: ['submitter'], name: 'owner_fk_idx'),
]
final class NexusRequestLog implements JsonSerializable
{
    #[
        ORM\Id,
        ORM\Column(name: 'id', type: 'uuid'),
    ]
    private Uuid $id;

    #[ORM\Column(name: 'submitted_at', type: 'datetimetz_immutable', nullable: false)]
    private ?DateTimeImmutable $submittedAt = null;

    #[
        ORM\ManyToOne(targetEntity: User::class),
        ORM\JoinColumn(name: 'owner_id', referencedColumnName: 'id', nullable: false),
    ]
    private ?User $submitter = null;

    #[ORM\Embedded(class: Request::class, columnPrefix: 'request_')]
    private ?Request $request = null;

    #[ORM\Embedded(class: Response::class, columnPrefix: 'response_')]
    private ?Response $response = null;

    public function __construct(?Uuid $id = null, ?Request $request = null, ?Response $response = null)
    {
        $this->id = $id ?? Uuid::v4();
        $this->request = $request;
        $this->response = $response;
    }

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

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function setRequest(?Request $request): void
    {
        $this->request = $request;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function setResponse(?Response $response): void
    {
        $this->response = $response;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'submittedAt' => $this->getSubmittedAt()?->format(DateTimeInterface::ISO8601),
            'submitter_id' => $this->getSubmitter()?->getId(),
            'request' => $this->getRequest(),
            'response' => $this->getResponse(),
        ];
    }
}
