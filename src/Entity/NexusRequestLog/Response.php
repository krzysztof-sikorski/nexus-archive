<?php

declare(strict_types=1);

namespace App\Entity\NexusRequestLog;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Embeddable]
final class Response implements JsonSerializable
{
    #[ORM\Column(name: 'completed_at', type: 'datetimetz_immutable', nullable: false)]
    private ?DateTimeImmutable $completedAt = null;

    #[ORM\Column(name: 'headers', type: 'json', nullable: true)]
    private ?array $headers = null;

    #[ORM\Column(name: 'status_code', type: 'integer', nullable: true)]
    private ?int $statusCode = null;

    #[ORM\Column(name: 'status_line', type: 'text', nullable: true)]
    private ?string $statusLine = null;

    #[ORM\Column(name: 'body', type: 'text', nullable: false)]
    private ?string $body = null;

    public function getCompletedAt(): ?DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?DateTimeImmutable $completedAt): void
    {
        $this->completedAt = $completedAt;
    }

    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    public function setHeaders(?array $headers): void
    {
        $this->headers = $headers;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function setStatusCode(?int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function getStatusLine(): ?string
    {
        return $this->statusLine;
    }

    public function setStatusLine(?string $statusLine): void
    {
        $this->statusLine = $statusLine;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): void
    {
        $this->body = $body;
    }

    public function jsonSerialize(): array
    {
        return [
            'completedAt' => $this->getCompletedAt()?->format(DateTimeInterface::ISO8601),
            'headers' => $this->getHeaders(),
            'statusCode' => $this->getStatusCode(),
            'statusLine' => $this->getStatusLine(),
            'body' => $this->getBody(),
        ];
    }
}
