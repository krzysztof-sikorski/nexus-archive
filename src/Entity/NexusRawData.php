<?php

declare(strict_types=1);

namespace App\Entity;

use App\Contract\Entity\BaseEntityInterface;
use App\Repository\NexusRawDataRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[
    ORM\Entity(repositoryClass: NexusRawDataRepository::class),
    ORM\Table(name: 'nexus_raw_data'),
    ORM\Index(columns: ['created_at', 'request_started_at', 'id'], name: 'nexus_raw_data_sorting_idx'),
    ORM\Index(columns: ['submitter_id'], name: 'nexus_raw_data_submitter_idx'),
]
class NexusRawData extends BaseEntity implements BaseEntityInterface, JsonSerializable
{
    #[
        ORM\ManyToOne(targetEntity: User::class),
        ORM\JoinColumn(name: 'submitter_id', referencedColumnName: 'id', nullable: false),
    ]
    private ?User $submitter = null;

    #[ORM\Column(name: 'request_started_at', type: Types::DATETIMETZ_IMMUTABLE, nullable: false)]
    private ?DateTimeImmutable $requestStartedAt = null;

    #[ORM\Column(name: 'response_completed_at', type: Types::DATETIMETZ_IMMUTABLE, nullable: false)]
    private ?DateTimeImmutable $responseCompletedAt = null;

    #[ORM\Column(name: 'method', type: Types::TEXT, nullable: false)]
    private ?string $method = null;

    #[ORM\Column(name: 'url', type: Types::TEXT, nullable: false)]
    private ?string $url = null;

    #[ORM\Column(name: 'form_data', type: Types::JSON, nullable: true)]
    private mixed $formData = null;

    #[ORM\Column(name: 'response_body', type: Types::TEXT, nullable: false)]
    private ?string $responseBody = null;

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'submittedAt' => $this->getCreatedAt()?->format(DateTimeInterface::ISO8601),
            'submitterId' => $this->getSubmitter()?->getId(),
            'requestStartedAt' => $this->getRequestStartedAt()?->format(DateTimeInterface::ISO8601),
            'responseCompletedAt' => $this->getResponseCompletedAt()?->format(DateTimeInterface::ISO8601),
            'method' => $this->getMethod(),
            'url' => $this->getUrl(),
            'formData' => $this->getFormData(),
            'responseBody' => $this->getResponseBody(),
        ];
    }

    public function getSubmitter(): ?User
    {
        return $this->submitter;
    }

    public function setSubmitter(?User $submitter): void
    {
        $this->submitter = $submitter;
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
