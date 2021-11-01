<?php

declare(strict_types=1);

namespace App\Entity\NexusRequestLog;

use DateTimeImmutable;
use DateTimeInterface;
use JsonSerializable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Request implements JsonSerializable
{
    #[ORM\Column(name: 'id', type: 'text', nullable: false)]
    private ?string $id = null;

    #[ORM\Column(name: 'started_at', type: 'datetimetz_immutable', nullable: false)]
    private ?DateTimeImmutable $startedAt = null;

    #[ORM\Column(name: 'method', type: 'text', nullable: false)]
    private ?string $method = null;

    #[ORM\Column(name: 'url', type: 'text', nullable: false)]
    private ?string $url = null;

    #[ORM\Column(name: 'headers', type: 'json', nullable: true)]
    private mixed $headers = null;

    #[ORM\Column(name: 'form_data', type: 'json', nullable: true)]
    private mixed $formData = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getStartedAt(): ?DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(?DateTimeImmutable $startedAt): void
    {
        $this->startedAt = $startedAt;
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

    public function getHeaders(): mixed
    {
        return $this->headers;
    }

    public function setHeaders(mixed $headers): void
    {
        $this->headers = $headers;
    }

    public function getFormData(): mixed
    {
        return $this->formData;
    }

    public function setFormData(mixed $formData): void
    {
        $this->formData = $formData;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'startedAt' => $this->getStartedAt()?->format(DateTimeInterface::ISO8601),
            'method' => $this->getMethod(),
            'url' => $this->getUrl(),
            'headers' => $this->getHeaders(),
            'formData' => $this->getFormData(),
        ];
    }
}
