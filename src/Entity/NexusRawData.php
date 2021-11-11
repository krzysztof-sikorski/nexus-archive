<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\NexusRawDataRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Uid\Uuid;

#[
    ORM\Entity(repositoryClass: NexusRawDataRepository::class),
    ORM\Table(name: 'nexus_raw_data'),
    ORM\UniqueConstraint(name: 'nexus_raw_data_uniq', columns: ['session_id', 'request_id']),
    ORM\Index(fields: ['submitter'], name: 'nexus_raw_data_submitter_idx'),
]
class NexusRawData implements JsonSerializable
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

    #[ORM\Column(name: 'session_id', type: 'text', nullable: false)]
    private ?string $sessionId = null;

    #[ORM\Column(name: 'request_id', type: 'text', nullable: false)]
    private ?string $requestId = null;

    #[ORM\Column(name: 'previous_request_id', type: 'text', nullable: true)]
    private ?string $previousRequestId = null;

    #[ORM\Column(name: 'request_started_at', type: 'datetimetz_immutable', nullable: false)]
    private ?DateTimeImmutable $requestStartedAt = null;

    #[ORM\Column(name: 'response_completed_at', type: 'datetimetz_immutable', nullable: false)]
    private ?DateTimeImmutable $responseCompletedAt = null;

    #[ORM\Column(name: 'method', type: 'text', nullable: false)]
    private ?string $method = null;

    #[ORM\Column(name: 'url', type: 'text', nullable: false)]
    private ?string $url = null;

    #[ORM\Column(name: 'form_data', type: 'json', nullable: true)]
    private mixed $formData = null;

    #[ORM\Column(name: 'response_body', type: 'text', nullable: false)]
    private ?string $responseBody = null;

    public function __construct(?Uuid $id = null)
    {
        $this->id = $id ?? Uuid::v4();
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'submittedAt' => $this->getSubmittedAt()?->format(DateTimeInterface::ISO8601),
            'submitterId' => $this->getSubmitter()?->getId(),
            'sessionId' => $this->getSessionId(),
            'requestId' => $this->getRequestId(),
            'previousRequestId' => $this->getPreviousRequestId(),
            'requestStartedAt' => $this->getRequestStartedAt()?->format(DateTimeInterface::ISO8601),
            'responseCompletedAt' => $this->getResponseCompletedAt()?->format(DateTimeInterface::ISO8601),
            'method' => $this->getMethod(),
            'url' => $this->getUrl(),
            'formData' => $this->getFormData(),
            'responseBody' => $this->getResponseBody(),
        ];
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

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function setSessionId(?string $sessionId): void
    {
        $this->sessionId = $sessionId;
    }

    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    public function setRequestId(?string $requestId): void
    {
        $this->requestId = $requestId;
    }

    public function getPreviousRequestId(): ?string
    {
        return $this->previousRequestId;
    }

    public function setPreviousRequestId(?string $previousRequestId): void
    {
        $this->previousRequestId = $previousRequestId;
    }

    public function getRequestStartedAt(): ?DateTimeImmutable
    {
        return $this->requestStartedAt;
    }

    public function setRequestStartedAt(?DateTimeImmutable $requestStartedAt): void
    {
        $this->requestStartedAt = $requestStartedAt;
    }

    public function getResponseCompletedAt(): ?DateTimeImmutable
    {
        return $this->responseCompletedAt;
    }

    public function setResponseCompletedAt(?DateTimeImmutable $responseCompletedAt): void
    {
        $this->responseCompletedAt = $responseCompletedAt;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(?string $method): void
    {
        $this->method = $method;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getFormData(): mixed
    {
        return $this->formData;
    }

    public function setFormData(mixed $formData): void
    {
        $this->formData = $formData;
    }

    /**
     * @return string|null
     */
    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }

    /**
     * @param string|null $responseBody
     */
    public function setResponseBody(?string $responseBody): void
    {
        $this->responseBody = $responseBody;
    }
}
